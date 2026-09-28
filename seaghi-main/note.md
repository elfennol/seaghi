# Some thoughts on code architecture

These are my few notes on what might be a good pragmatic architecture for an application.

## Seaghi Architecture

Seaghi comes from the contraction of seagull and archi. A way of giving a name to my way of seeing architecture.

Architectures like the hexagonal architecture used with DDD are complex and heavy. Lot of code, mapping hell, bloated domain objects trap, too many technical questionings, books that need hundreds of pages to explain the basis... Really productive architecture?

The goal here is to take some good parts and compromise to have less code, less technical questionings, clear architecture, maintainable code, and mostly KISS code. We want to:
- Quickly see infrastructure dependencies (MySQL, Redis, files system, ...)
- Quickly see functional use cases
- Have use case classes with less technical vocabulary and more business language oriented
- Test a use case without the infrastructure dependencies
- Update a framework more easily

General diagram:

![duck_archi_diag.png](duck_archi_diag.png)

Picture generated with:

```sh
make deptrac-diag
```

## Overview

![overview.svg](overview.svg)

Picture generated with:

```sh
dot -T svg -o overview.svg overview.gv
```

## Tree

```
├── Application
│   ├── Enum                # Domain enumerations
│   ├── Rule                # Business rules and calculations
│   └── UseCase             # Functional use cases (concrete, directly called by controllers)
├── Entity                  # Rich Domain / ORM Entities & Value Objects (Invariants & State transitions)
├── Infrastructure          # Technical delivery & adapters
│   ├── Client              # External services call
│   ├── EventListener       # Event listeners & HTTP exception translation
│   ├── HttpApi             # Internal API
│   │   ├── Controller
│   │   └── Dto
│   ├── Messenger           # Message manager & handlers
│   └── Persistence         # Persistence (SQL, ...): repo adapters, identity generator & query projections
└── Port                    # Outbound interfaces & Cross-layer contracts
    ├── DataContract        # Cross-layer DTOs (Use Case inputs/outputs, read projections, async messages)
    └── Out                 # Outbound interfaces (Repositories, Clients, Identity, Messenger, Transactions)
```

## What is Infrastructure?

Infrastructure is here to speak with SQL DB, Redis, RabbitMQ, HTTP API, local folder, ... All those very annoying external resources when we want to test only the use cases.

## What is Application?

The set of the use cases without being concerned with technical resources.

I have at least two folders in Application:
- UseCase: objects with only one public method representing a business use case.
- Rule: business rules and calculations that do not belong directly in an entity (e.g. calculation algorithms, strategies, or rules requiring external collaborators). When passing data to a Rule or helper, pass only the required primitives or Value Objects (following the Interface Segregation Principle), not the full ORM entity graph. This keeps rules reusable and trivial to unit-test.

## What is the Domain? (Pragmatic Domain: Combining Domain & Entity)

Rather than maintaining two parallel object hierarchies (a pure Domain object and an ORM Entity) and suffering from "mapping hell", we pragmatically combine the Domain model with the persistence model in **Rich Entities**.

Doctrine ORM is configured directly on these entities, but they are **not anemic**:
- **No naive public setters:** We avoid generic setters like `setAvailable(bool)` or `setLevel(int)` that leave the entity open to arbitrary, illegal state mutations.
- **Intention-revealing business methods:** State transitions are guarded by semantic methods (e.g. `markAsSold()`, `levelUp()`, `isReadyToFight()`).
- **Make illegal states unrepresentable:** An entity must always represent a valid state. We do not allow an entity to be in an invalid state during its lifecycle.
- **Tell, Don't Ask:** Instead of extracting entity fields to check logic externally in a service, we tell the entity what action to perform.

### Preventing Infrastructure Leaks in Rich Entities:
Even though entities use Doctrine mapping attributes directly, we must prevent infrastructure mechanics from polluting application logic:
- **Avoid Lazy-Loading in Application logic:** When a Use Case requires related entities, the repository must eager-load them (e.g. DQL `JOIN FETCH`). Accessing an uninitialized relation in Application should never silently trigger hidden SQL queries or throw detached proxy exceptions.
- **Keep Collection signatures native:** Internally, entities can use `Doctrine\Common\Collections\Collection`, but public getters must return a native PHP `array` (`$this->skills->toArray()`) or native `iterable`. This prevents Doctrine interfaces from leaking into Application signatures.
- **Defensive copying:** Returning `$collection->toArray()` ensures callers cannot mutate the entity's internal collection from the outside.
- **Favor domain actions over exposing collections:** Prefer methods like `$monster->learnSkill($skill)` or `$monster->hasSkill($code)` rather than exposing raw collections.

### Preventing Entity Bloat:
To prevent entities from growing into "God objects":
1. **Scope to Bounded Contexts:** An entity model in `Shop` is distinct from an entity in `Battle`, even if they share or link to the same database tables.
2. **CQRS for Reads:** Entities are strictly write models (for state changes and invariants). Display, query, and search operations bypass the entity completely: read query adapters (e.g. `SearchMonsterPort`) project SQL/DQL directly into read DTOs (`SearchMonsterDto`), without hydrating ORM entities or tracking them in the Unit of Work.
3. **Value Objects & Invariants:** Group cohesive properties and behavior into immutable Value Objects (e.g. `Price`, `Health`) or enforce invariants directly via guard clauses in the entity constructor.
4. **Business Rules & Calculations:** Calculation algorithms, business policies, or strategies that evolve independently or do not belong in an entity go into dedicated classes in `Application/Rule`, while intrinsic state invariants remain inside the entity.

- https://www.martinfowler.com/bliki/AnemicDomainModel.html
- https://martinfowler.com/bliki/TellDontAsk.html
- https://enterprisecraftsmanship.com/posts/having-the-domain-model-separate-from-the-persistence-model/
- https://khorikov.org/posts/2020-04-20-when-do-you-need-persistence-model/

## What is Port

Port represents the boundary between Application and Infrastructure:

### 1. Inbound: No 1-to-1 `Port\In` Interfaces (Pragmatic Inward Dependencies)
We intentionally **do not create 1-to-1 interfaces for Use Cases** (no `BuyItemPort` for `BuyItem`).
- **Clean Architecture Dependency Rule:** Outer delivery mechanisms (Controllers, CLI commands, Message Handlers) naturally depend **inward** on the Application core (`Controller -> UseCase`).
- **KISS & Zero Boilerplate:** Business Use Cases almost never have multiple implementations in production. Dropping `Port\In` interfaces eliminates redundant single-method interfaces and allows Symfony to autowire Use Cases directly with zero manual configuration in `services.yaml`.
- **Testing:** Controllers can easily stub or mock concrete Use Cases in PHPUnit without requiring an interface.

### 2. Outbound (`Port/Out`): Strict Dependency Inversion
The Application layer must never depend on external technical infrastructure (Doctrine ORM, Redis, RabbitMQ, HTTP APIs).
- Outbound interfaces (`MonsterRepositoryPort`, `TransactionPort`, `IdentityGeneratorPort`, `WithdrawFromAccountPort`, `SendMessagePort`) live in `Port/Out`.
- Infrastructure implements these interfaces in `Infrastructure/Persistence` (repositories, transactions, identity generator), `Infrastructure/Client`, `Infrastructure/Messenger`, etc.

### 3. DataContracts (`Port/DataContract`)
Immutable DTOs exchanged across layer boundaries:
- Use Case inputs and outputs (e.g. `BuyItemDto`), which also serve as the public API response contracts returned by controllers.
- CQRS read projections (e.g. `SearchMonsterDto`).
- Asynchronous message data holders dispatched to message brokers (e.g. `MonsterSoldMessage`).
- We do not distinguish between `DataContract` and `MessageContract`: all are immutable data transfer objects (DTOs) without behavioral methods. `SendMessagePort::send(object $message)` accepts any message DTO directly without requiring empty marker interfaces.

## What is a (bounded) context?

From "Patterns, Principles, and Practices of Domain-Driven Design" (Scott Millet):

> A bounded context is a linguistic boundary. It isolates models to remove ambiguity within UL.

UL: Ubiquitous Language (common language). 

Two contexts here: Shop and Battle.

We can communicate between contexts in many ways:
- By directly calling the objects (only via interfaces)
- With HTTP calls (`symfony/http-client`)
- With messages (`symfony/messenger`)
- ...

## Data holder objects are immutable

Data holder objects (DTO, Message, ...) are immutable to avoid side effects and to ease debugging. It is a final and valid unit of data. We know the layer that created this data object, and we know that this object is not modified during its journey across layers.

- DTOs should be used for only one use case. Avoid sharing the same DTO across multiple use cases (to prevent coupling changes between features).
- When used with a controller, use one DTO for one view. Do not use one DTO for multiple views. Same for message handlers.
- All cross-layer DTOs (Use Case inputs/outputs, read projections, and asynchronous messages) live under `Port/DataContract`.
- We avoid empty marker interfaces: `SendMessagePort::send(object $message)` handles any message DTO directly.

## Value Objects & Invariant Guards

- **KISS default rule:** Invariants specific to an entity are validated directly in the entity constructor via guard clauses (`if ($price < 0) throw new InvalidArgumentException(...)`). This prevents illegal states without creating class proliferation.
- **Value Objects when justified:** Group related properties into an immutable Value Object when the concept is composite (e.g. `Health` with current and max values, `Money` with amount and currency) or when validation/calculation logic is reused across multiple entities.
- In Doctrine, Value Objects live in `Entity/` (or `Entity/ValueObject/`) and use `#[ORM\Embeddable]` to embed directly into the entity table without join overhead.
- Prefer using static factory methods with private constructors: clearer intent, multiple constructors, and a single entry point.

## Entities are mutable (Identity & Null Safety)

An Entity has an identity and changes during its lifetime. For example, a customer address may change, but it is still the same customer.

- **Non-Nullable Identity (Null Safety):** An entity must never exist in an "incomplete" state in memory with a `null` ID. Its ID is assigned at instantiation and is strictly non-nullable (`private readonly Uuid $id`).
- **Decoupled Identity Generation:** The Use Case does not know the technical mechanics of UUID creation. Instead, it relies on a dedicated outbound port:
  ```php
  namespace App\Shop\Port\Out;

  interface IdentityGeneratorPort
  {
      public function generate(): Uuid;
  }
  ```
  The infrastructure adapter (`Infrastructure/Persistence/IdentityGenerator`) injects Symfony's `UuidFactory` and generates time-ordered UUIDv7 based on `config/packages/uid.yaml`.
- **Doctrine `strategy: 'NONE'`:** By omitting `#[ORM\GeneratedValue]`, Doctrine automatically persists the application-assigned ID. During reads, Doctrine reconstitutes the entity via reflection without invoking the constructor.
- The ID, once assigned, never changes. Never define a setter for the ID.
- Avoid exposing public setters for mutable state. Use semantic methods (`changeAddress()`, `markAsSold()`) that enforce invariants before updating internal properties.
- Entities should remain shielded from external layers: Controllers and API adapters receive DTOs (DataContracts), never entities directly.

## Controller

Use one route for one view. Few code (logs + a method for validation + a method for processing). Do not use one route for several views. Decouple the controller and view: here I use `HttpApiSerializeSubscriber` but there are several ways to solve this problem.

- https://en.wikipedia.org/wiki/Action–domain–responder

## Commands and message handlers

Minimal code here (logs + a method for validation + a method for processing).

## One public method in a use case

Prefer one public method in a use case. It's easier to see use cases at a glance just by looking at the class names.

## A service does not contain a state

A service must be stateless.

## Library (dependency)

### What Can Go in the Domain Core?

You can freely use external libraries in your domain/application layers if they satisfy three conditions:

- **Pure in-memory operations:** They do not perform network requests, file access, or database I/O.
- **Stable & lightweight:** They have no transitively heavy framework dependencies.
- **Represent generic concepts or specifications:** Money, math, string manipulation, dates, or validation assertions.

### What MUST Stay in the Infrastructure Layer?

Any dependency that touches external state, framework containers, or I/O drivers belongs exclusively in adapters and infrastructure:

- **Framework Core**
- **Persistence / ORM**
- **Network & Serialization**
- **Third-Party APIs:**

## No Short-Circuiting (Strict Layer Separation)

An inbound delivery adapter (Controller, CLI command, Message Handler) must **never directly call** an outbound infrastructure adapter (API client, database query, external service). Every interaction must go through an `Application` Use Case (or Query).

Even if the Use Case is only 3 lines of orchestration, passing through `Application`:
- Preserves the functional visibility of all capabilities within `Application/UseCase`.
- Maintains strict boundary insulation between Inbound and Outbound adapters.
- Allows testing the business flow in pure, fast unit tests without requiring HTTP or framework infrastructure.

## Autowiring (Symfony)

We can exclude `Port` and `Entity` folders from service discovery.

Because Use Cases are concrete classes, Symfony autowires them automatically into Controllers with **zero configuration** in `services.yaml`.

For outbound interfaces in `Port/Out` (`MonsterRepositoryPort`, `TransactionPort`), Symfony autowires them automatically to their respective Infrastructure implementation classes. Manual aliases in `services.yaml` are only required if an interface has multiple implementations in the same environment.

## Exceptions & Result Handling

Important rule: **throw early, catch late**.

1. **Expected Business Outcomes vs Exceptions:**
   - Prefer returning typed Result DTOs (e.g. `BuyItemDto(success: false, rejectionReason: SaleRejectionReason::NOT_READY_TO_FIGHT)`) for predictable business rejections (inspired by `Result<T, E>` in languages like Rust). Avoid using exceptions as normal control flow.
   - Reserve Exceptions for broken invariants (`InvalidArgumentException`, `LogicException`) or missing resources (`MonsterNotFoundException`).
2. **Avoid Exception Proliferation:**
   - Do not create a separate exception class for every situation. Leverage standard PHP exceptions, and introduce custom domain exceptions only when distinct recovery or handling logic is needed.
3. **Zero Framework Attributes in Application:**
   - Never put framework attributes (like Symfony's `#[WithHttpStatus]`) in `Application` or `Port`. ORM-specific attributes are permitted on entities and value objects for pragmatic reasons.
4. **Infrastructure Translates to HTTP:**
   - The delivery layer handles translation centrally (e.g. via an `ApiExceptionSubscriber` in `Infrastructure/EventListener` listening to `kernel.exception`) to map domain exceptions into appropriate HTTP status codes (404, 422, 500) without duplicating try/catch blocks in controllers.

## Avoid inheritance

Avoid inheritance. Use composition.

## Repositories & Persistence (Ports & Transactions)

### 1. Typed Repository Ports over Generic CRUD
Do not use a generic `FindEntityPort` or `PersistEntityPort` that handles `object`. Generic ports destroy compile-time type safety, disable PHPStan static analysis, and force brittle `/** @var */` and `assert()` annotations everywhere.

- **Split repositories per Aggregate Root:** Create dedicated, typed outbound ports (e.g. `MonsterRepositoryPort`) in `Port/Out`.
- **Differentiate `get()` and `find()`:**
  - `get(Uuid $id): Monster` throws a `MonsterNotFoundException` immediately if not found (eliminating repetitive null checks in Use Cases).
  - `find(Uuid $id): ?Monster` returns nullable when absence is a normal, expected business outcome.
- **Save explicitly:** `save(Monster $monster): void` declares the entity for persistence.

### 2. Atomic Transactions via `TransactionPort::run(callable)`
Avoid leaking technical ORM jargon like `flush()` or `unitOfWork` into Use Cases. Instead, use an explicit, business-oriented transaction runner:

```php
namespace App\Shop\Port\Out;

interface TransactionPort
{
    /**
     * @template T
     * @param callable(): T $operation
     * @return T
     */
    public function run(callable $operation): mixed;
}
```

- **Implementation in Infrastructure:** Implemented via Doctrine's `$entityManager->wrapInTransaction($callable)`.
- **Performance:** Avoids multiple flushes across services. A single transaction computes change-sets once, minimizes database round-trips, and shortens row lock durations.
- **Predictable & Universal:** Works identically across Web Controllers, Messenger workers, CLI commands, and PHPUnit integration tests without fragmented, magic event listeners.
- **Automatic Rollback:** If an exception is thrown inside the callable, the transaction rolls back automatically.

### 3. Safe Event & Message Dispatching (Transactional Outbox)
Never dispatch asynchronous messages (e.g. RabbitMQ, Redis) inside or before the database transaction:
- Dispatch messages strictly **after** `$this->transaction->run()` commits successfully.
- If the transaction fails or rolls back, execution halts and the message is never sent.
- **The Dual-Write Tradeoff:** Dispatching post-commit leaves a small residual risk: if the database commits and the process or message broker crashes immediately afterwards before `send()`, the message is lost. In Symfony 7.4, post-commit dispatching is an accepted pragmatic compromise. For mission-critical consistency, the target pattern is the **Transactional Outbox Pattern** (natively supported via `OutboxMiddleware` in Symfony 8.2+ or via Symfony Messenger's Doctrine transport), which stores the message in the database within the same atomic SQL transaction.
- **Test in PHPUnit:** Always write a unit test with `$sendMessageMock->expects($this->never())->method('send')` to verify that when a transaction fails, no message is dispatched.

## Comment

Avoid using the annotations `@return`, `@param`, `@var`. It is often useless. We can use it for an array: `MyObject[]`.

Prefer this style:

```php
/**
 * Roll a $dice.
 *
 * The result of this action is a number of ones of the faces.
 * You may add a $modifier to add to the result.
 */
public function roll(Dice $dice, int $modifier = 0): int
{

}
```

Dart uses this style: https://dart.dev/guides/language/effective-dart/documentation#do-use-prose-to-explain-parameters-return-values-and-exceptions

## Null safety

Prefer a null safety approach.

## Continuous improvement

Continuous improvement is crucial. Don't be afraid to continually refactor/update/improve the code. It is vital. If we don't do this, the application will degrade beyond repair.

## Duplicate code

Do not fear duplicating code between context. Do not create common code between context. For example, here we have the context Shop and the context Battle.

## Validations

Validations are divided into three distinct levels:

1. **Infrastructure (Input & Format Validation):** Technical input validation on DTOs/Requests via attributes or constraints (e.g. valid JSON format, non-empty string, valid UUID format).
2. **Entity & Value Objects (Domain Invariants):** Invariants that must be true at all times (e.g. a level cannot be negative, a sick monster cannot fight, price must be greater than zero). Protected by private constructors, Value Objects, and entity business methods.
3. **Application (Contextual Business Rules & Policies):** Complex business rules involving external collaborators or multi-entity coordination (e.g. checking customer balance against an account API, verifying battle season rules).

## Use a tool to check the dependencies

For example: https://github.com/deptrac/deptrac

## Tests

Different types of tests are possible:

- Classic unit tests for each method
- Test on a use case (mock Port)
- Integration tests
  - When a test interacts with a DB, it should roll back the changes to not impact the other tests.
  - Mock the clients
- Functional tests

Add a test when you encounter a bug.

## Books

- Patterns, Principles, and Practices of Domain-Driven Design (Scott Millett)
- Get Your Hands Dirty on Clean Architecture (Tom Hombergs)
