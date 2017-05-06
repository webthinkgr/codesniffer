# WebthinkSniffer

A collection of Codesniffer rules.

## Description

This package is a collection of Codesniffer rules. Some of them are custom rules and some of them are
copied or inspired from other packages.

**Why?**
1. I have found various rules from many packages. In order to apply all these rules to a project of mine
   I would have to require all these packages and create a `*.xml` file with the rules I need.
   This would not be handy. I don't like to be dependent in so many packages.
   Furthermore some of these rules I needed them altered to server my needs and it would be quite difficult
   creating issues or PR for any specific need.
2. I don't like the CodeStyle that `squizlab/codesniffer` has. This codestyle is followed by many of the sniffs
   I found at these external packages and does not make it easy for me writing PRs for fixing some issues.
3. It's package had it's own way of testing the Sniffs.
4. Some packages required installing them globally.
5. For performance issues. A sniff might check more than I needed. So stripping off what I don't need might make 
things a little faster.

## About copied Sniffs 
If you believe that for any reason this package or a part of it is not respecting the licenses and copyrights
please open an issue and advise accordingly.

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