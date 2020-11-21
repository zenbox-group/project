# ZenBox Project

[![PHP Version](https://img.shields.io/packagist/php-v/zenbox/project.svg?style=for-the-badge)](https://packagist.org/packages/zenbox/project)
[![Stable Version](https://img.shields.io/packagist/v/zenbox/project.svg?style=for-the-badge&label=Latest)](https://packagist.org/packages/zenbox/project)
[![Total Downloads](https://img.shields.io/packagist/dt/zenbox/project.svg?style=for-the-badge&label=Total+downloads)](https://packagist.org/packages/zenbox/project)

Use this skeleton application based on [Laminas Mezzio](https://docs.mezzio.dev/mezzio/) to quickly setup and start working on a new application.

This application uses:
## What components does application use?

- [Laminas Mezzio](https://docs.mezzio.dev/mezzio/)
- [Symfony Twig](https://twig.symfony.com/)
- [Symfony Console](https://symfony.com/doc/current/components/console.html)
- [Doctrine ORM](https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/index.html)
- [Doctrine Migrations](https://www.doctrine-project.org/projects/doctrine-migrations/en/3.0/index.html)
- [Doctrine Fixtures](https://github.com/doctrine/data-fixtures)

## Getting Started

Start your new project with composer:

```bash
$ composer create-project zenbox/project <project-path>
```

After choosing and installing the packages you want, go to the
`<project-path>` and start web server to verify installation

You can use `docker-compose` to run the app with `docker`, so you can run these commands:
```bash
cd <project-path>
docker-compose up -d
```
After that, open `http://localhost` in your browser.
