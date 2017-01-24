# WebthinkSniffer

A collection of Codesniffer rules.

## Description

This package is a collection of Codesniffer rules. Some of them are custom rules and some of them are
copied from other packages.

**Why?**
1. I have found various rules from many packages. In order to apply all these rules in to a project of mine
   I would have to require all these packages and create a `*.xml` file with the rules.
   This would not be handy. I don't like to be dependent in so many packages.
   Furthermore some of these rules I needed them altered to server my needs.
2. I don't like the CodeStyle that `squizlab/codesniffer` has. 

## Sniffs included

- Please read [this](SNIFFS.md) to learn more about the ruleset.

## Install

You can install this package through composer

    $ composer require webthink/codesniffer

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

For now there are no test. I hope I will write some soon.

## Todo

- Write tests
- Create a wiki