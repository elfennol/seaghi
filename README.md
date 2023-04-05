# An example on my pragmatic (controversial?) choices on code architecture

Before you raise your arms to the sky with wet and watery eyes moaning with all your soul, please read my notes on this code: [seaghi-main/note.md](seaghi-main/note.md).

If you want to share your thoughts on this architecture: [Your thoughts on this architecture](https://github.com/elfennol/seaghi/discussions/categories/your-thoughts-on-this-architecture)

## Install

See [seaghi-main/INSTALL.md](seaghi-main/INSTALL.md).

## Play

The domain is a RPG game divide in two contexts: Shop and Battle. Account simulate an external provider.

See [seaghi-qa/functional-tests/play.http](seaghi-qa/functional-tests/play.http). You can execute the requests directly in PHPStorm. Base urls are defined in [seaghi-qa/functional-tests/http-client.env.json](seaghi-qa/functional-tests/http-client.env.json).

## QA

See [seaghi-main/qa.md](seaghi-main/qa.md).
