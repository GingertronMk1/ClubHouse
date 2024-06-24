# ClubHouse

## `src` Structure

### Domain

The base level. This level is how things get turned into DB objects

This contains: 

- Repository interfaces that are picked up at the `Infrastructure` level
- Basic classes that extend either other types in `Domain` or PHP basic types
- Write models

### Application

This contains:

- Finder interfaces that are picked up at the `Infrastructure` level
- Read models

### Infrastructure

This is where external services come into play.
This layer contains implementations for the aforementioned interfaces, and any external APIs.

### Framework

Framework-specific code, such as Symfony's `UserProvider`.

### Deptrac config

```yaml
Framework:
    - Infrastructure
    - Application
    - Domain
Infrastructure:
    - Application
    - Domain
Application:
    - Domain
Domain: ~
```

### What does this all mean?

When we're adding a new entity we should add the following:

- `src/Domain/{ENTITY}/ValueObject/{ENTITY}Id` - probably an extension of `AbstractUuidId`
- `src/Domain/{ENTITY}/{ENTITY}RepositoryInterface`
- `src/Domain/{ENTITY}/{ENTITY}` - this will be your write model
- `src/Application/{ENTITY}/Command/Create{ENTITY}Command`
- `src/Application/{ENTITY}/Command/Edit{ENTITY}Command`
- `src/Application/{ENTITY}/CommandHandler/Create{ENTITY}CommandHandler`
- `src/Application/{ENTITY}/CommandHandler/Edit{ENTITY}CommandHandler`
- `src/Application/{ENTITY}/{ENTITY}FinderInterface`
- `src/Application/{ENTITY}/{ENTITY}` - this will be your read model
- `src/Infrastructure/{ENTITY}/Dbal{ENTITY}Finder`
- `src/Infrastructure/{ENTITY}/Dbal{ENTITY}Repository`
- `src/Framework/Controller/{ENTITY}Controller`
- `src/Framework/Form/Create{ENTITY}Type`
- `src/Framework/Form/Edit{ENTITY}Type`