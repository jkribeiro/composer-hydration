[![Packagist version](https://img.shields.io/packagist/v/jkribeiro/composer-hydration.svg)](https://packagist.org/packages/jkribeiro/composer-hydration)
[![Packagist Downloads](https://img.shields.io/packagist/dt/jkribeiro/composer-hydration.svg)](https://packagist.org/packages/jkribeiro/composer-hydration)

# composer-hydration

## Introduction
__composer-hydration__ is a simple package that provides a __Composer Script__ to be used as placeholder replacement, mostly used by 'skeletons' projects.

Example:

```
composer run-script hydrate -- --replace={FRUIT}:"apple",{INGREDIENT}:"cinnamon"
```
The script will search for the placeholders in `file content`, `file names` and `folders`.

Before:
```
$ /path/composer/project/{FRUIT}.txt
"I love {FRUIT} with {INGREDIENT}, is a good combination!"
```

After:
```
$ /path/composer/project/apple.txt
"I love apple with cinnamon, is a good combination!"
```

## Installation

### Install Composer
Since __composer-hydration__ is a Composer script, you need to [install composer](https://getcomposer.org) first.
> Note: The instructions below refer to the [global composer installation](https://getcomposer.org/doc/00-intro.md#globally).
You might need to replace `composer` with `php composer.phar` (or similar)
for your setup.

### Add package dependency
Add __composer-hydration__ as package dependency of your project, updating your `composer.json`:

```
    "require": {
        ...
        "jkribeiro/composer-hydration": "~1"
    }
```

### Define the Composer Script
Define the Composer script, adding this entry to your `composer.json`:
```
    "scripts": {
        "hydrate": "Jkribeiro\\Composer\\ComposerHydration::meatOnBones"
    }
```

### Install Project
```
composer install
```

## Usage
There are some ways that you can execute this script:

### Execute the command manually
After have the package installed, you can run the command manually to have your values placed.
```
composer run-script hydrate -- --replace={SEARCH}:{REPLACE},..."
```

### Hydrate during the Composer Events
Composer fires [some events](https://getcomposer.org/doc/articles/scripts.md#event-names) during its execution process, useful to define on which step/event it will perform the hydration process.

In the example below, the hydration process will occur after the project installation:
```
    "scripts": {
        "hydrate": "Jkribeiro\\Composer\\ComposerHydration::meatOnBones",
        "post-install-cmd": "@composer run-script hydrate -- --replace={{PROJECT_NAMESPACE}}:{%BASENAME%}"
    }
```

## Variables as Replacement values
Sometimes we need to use dynamic replacement values on `composer.json`, not only hardcoded values like `{FRUIT}:banana`, for these cases, there are two possibilities:

### Environment Variables
`composer.json` allows environment variables as replacement placeholder value, like `{{PROJECT_NAMESPACE}}:$PROJECT_NAME"`, `$PROJECT_NAME` is the variable name. You must define the variables before execute the Composer commands.

Example:  
__composer.json__
```
    ...
    "scripts": {
        "hydrate": "Jkribeiro\\Composer\\ComposerHydration::meatOnBones",
        "post-install-cmd": "@composer run-script hydrate -- --replace={{PROJECT_NAMESPACE}}:$PROJECT_NAME"
    }
```

 __Execution__
```
$ export PROJECT_NAME="My Project"
$ composer install
```

### "Magic Constants"
Using the same idea of [PHP Magic constants](http://php.net/manual/en/language.constants.predefined.php), __composer-hydration__ provides some Magic constants too.

- `{%BASENAME%}`: Returns the base folder name where the script is being executed, normally is the name of the project.
- `{%UCFIRST_BASENAME%}`: Returns the base folder name with the first character capitalized.

  Example:

  ```
  $ ~/Projects/myproject: composer run-script hydrate -- --replace={{PROJECT_NAMESPACE}}:{%BASENAME%}"
  ```
  Placeholders with `{{PROJECT_NAMESPACE}}` will be replaced by `myproject`.
