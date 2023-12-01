# Pagination

## Install

### Instal the bundle

```shell
comoser require osw3/symfony-pagination
```

### Prepare for update

In your composer.json file, change the line of the dependency to prepare futures updates of the bundle.

```json
"osw3/symfony-pagination": "*",
```

### Enable the bundle

Add the bundle in the `config/bundle.php` file.

```php
return [
    OSW3\SymfonyPagination\OSW3SymfonyPaginationBundle::class => ['all' => true],
];
```

## Usage

```twig
{{ pagination({
    paginationService: paginationService,
    route: 'app_book_index',
    absolute: true
}) }}
```

```twig
{{ pagination_total() }}
```

```twig
{{ pagination_pages() }}
```

```twig
{{ pagination_page() }}
```

```twig
{{ pagination_per_page() }}
```
