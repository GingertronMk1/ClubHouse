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
