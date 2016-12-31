# Tegdesign.com

This site is using the Jigsaw static site generator. Full details on my blog post can be found by visiting this URL: http://www.tegdesign.com/setting-up-my-new-static-site-using-jigsaw/

Getting started.

```sh
composer update
npm install
npm install -g browserify
npm install -g gulp
```

When dev'ing to preview changes:
```sh
gulf watch
```

When adding new pages:
```sh
jigsaw build
```

When deploying:
```sh
jigsaw build production
```