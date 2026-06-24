# DEIMS Core

DEIMS Core provides the basic institute profile configuration for the **Drupal Education Institute Management System**.

This module extends Drupal’s **Basic site settings** form and adds institute-specific fields such as EIIN Number, Institution Type, Education Board, MPO Status, Institute Code, and Establishment Year.

## Features

- Adds DEIMS institute information fields to Drupal Basic site settings.
- Stores institute profile data in Drupal configuration.
- Uses Drupal 11 OOP hook implementation with `src/Hook`.
- Provides config schema for proper configuration management.
- Supports Drupal 11 and Drupal 12.

## Added Fields

The module adds the following fields under:

```txt
/admin/config/system/site-information
```
