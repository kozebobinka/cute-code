# cute-code

### Run

```
php app.php input.txt
```

### Currency exchanger setup

Exchange service provided in the task isn't work, so I use it from other endpoint, which needs api key.

You need to have .env file like .env.dist

Put there an api key for apilayer. If you don't have, use mine:

```
SlPI5AFpQsFcfyecJG9YRRdSG9tdZyWL
```

### Run tests

```
vendor/bin/phpunit tests --test-suffix=TestCase.php
```