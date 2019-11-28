Contributions are welcome, and are accepted via pull requests. Please review these guidelines before submitting any pull requests.

#### Guidelines

* Please follow the [PSR-12 Coding Standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-12-extended-coding-style-guide.md).
* Ensure that the current tests pass, and if you've added something new, add the tests where relevant.
* Remember that we follow [SemVer](http://semver.org). If you are changing the behaviour, you may need to update the docs.
* Send a coherent commit history, making sure each individual commit in your pull request is meaningful. If you had to make multiple intermediate commits while developing, please [squash](http://git-scm.com/book/en/Git-Tools-Rewriting-History) them before submitting.
* You may also need to [rebase](http://git-scm.com/book/en/Git-Branching-Rebasing) to avoid merge conflicts.

#### Docker Image

To check the library, in the lib root path, you must to use docker to create the image:

```sh
docker build -t datalayer .
```

If you've updated or added something, you have to generate the docker image again, because the source code is adding into image.

#### Testing

Execute the unit tests by docker's image created:

```sh
docker run -ti datalayer ./vendor/bin/phpunit ./tests
```

If the test suite passes on your local machine you should be good to go.

#### Coding Style

Check if the source code agrees with [PSR-1](https://www.php-fig.org/psr/psr-1/) and [PSR-12](https://www.php-fig.org/psr/psr-12/) conventions, using [CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer):

```sh
docker run -ti datalayer ./vendor/bin/phpcs src/ --extensions=php --standard=PSR1,PSR12
```

#### PHPStan

Discover bugs in all code using [PHPStan](https://github.com/phpstan/phpstan):

```sh
docker run -ti datalayer php ./vendor/bin/phpstan analyse -l 4 -c phpstan.neon src/
```

#### PHPMD

Equivalent to Java PMD tool, looking foward to finding messy code using [PHPMD](https://github.com/phpmd/phpmd):

```sh
docker run -ti datalayer ./vendor/bin/phpmd src/ text phpmd.xml
```

---

by [c0dehappy](https://github.com/c0dehappy)
