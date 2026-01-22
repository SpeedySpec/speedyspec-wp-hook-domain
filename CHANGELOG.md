# Changelog

## [1.1.1](https://github.com/SpeedySpec/speedyspec-wp-hook-domain/compare/v1.1.0...v1.1.1) (2026-01-22)


### Bug Fixes

* documentation and tests improvements ([b72af1b](https://github.com/SpeedySpec/speedyspec-wp-hook-domain/commit/b72af1bde36a8f50bbe6373000cacfff31bc73b1))


### Documentation

* add docblocks to every php element ([ab1fbf7](https://github.com/SpeedySpec/speedyspec-wp-hook-domain/commit/ab1fbf78226646a1dda037c6c137adf4773f803b))

## [1.1.0](https://github.com/SpeedySpec/speedyspec-wp-hook-domain/compare/v1.0.1...v1.1.0) (2026-01-22)


### Features

* Merge pull request [#5](https://github.com/SpeedySpec/speedyspec-wp-hook-domain/issues/5) from SpeedySpec/refactoring ([07e8789](https://github.com/SpeedySpec/speedyspec-wp-hook-domain/commit/07e8789d652b31c4bb7cc85f08daf39cc456f435))
* refactor Observer implementation and references. ([92a9302](https://github.com/SpeedySpec/speedyspec-wp-hook-domain/commit/92a930236ef5308ae4089387ec903de42493cec6))
* remove `SetupHookApiUseCase` since the Service Provider should handle it ([2a2d13a](https://github.com/SpeedySpec/speedyspec-wp-hook-domain/commit/2a2d13a00f6f3588ebd877141ab36695ecf0dff3))
* rename `Invoke*Hook` to better format `*HookInvoke` and to better match the interface ([929908c](https://github.com/SpeedySpec/speedyspec-wp-hook-domain/commit/929908cbe6c5a129c10247791a9945822b08474d))


### Bug Fixes

* add strict type declaration and update tests ([bec7403](https://github.com/SpeedySpec/speedyspec-wp-hook-domain/commit/bec7403ac7cf8db6d99f647385bcff82f4e435d3))
* corrections to Use Case references ([ed12fca](https://github.com/SpeedySpec/speedyspec-wp-hook-domain/commit/ed12fca68ccda208d61d5b5b62d4f9bb315f935d))
* include hook api functions for backwards compatibility and drop-in replacement for WordPress functions ([60965ef](https://github.com/SpeedySpec/speedyspec-wp-hook-domain/commit/60965ef91fd8e91d6aee9efbab4042fdebec3c52))
* update composer package and add wordpress license. ([230fe12](https://github.com/SpeedySpec/speedyspec-wp-hook-domain/commit/230fe1222b8b8bbab8427a43f497f346df709a8e))
* updates to complete use cases for legacy code. ([01bb1c0](https://github.com/SpeedySpec/speedyspec-wp-hook-domain/commit/01bb1c0bf41b322f12f352793f97837507e58cf1))


### Documentation

* add comprehensive guide documentation for the domain layer of the Hook API ([b30b4eb](https://github.com/SpeedySpec/speedyspec-wp-hook-domain/commit/b30b4eba0dfbc8af0d7dafa24c15da492d4fe4fc))
* added CLAUDE.md and AGENTS.md for prompts ([f8698eb](https://github.com/SpeedySpec/speedyspec-wp-hook-domain/commit/f8698eb1c174c9956e7b9a5097191bf397871638))
* update services to expand with architecture and other details ([eba355f](https://github.com/SpeedySpec/speedyspec-wp-hook-domain/commit/eba355f157d1091a3b7bfb1445e2bf7b8db23427))

## [1.0.1](https://github.com/SpeedySpec/speedyspec-wp-hook-domain/compare/v1.0.0...v1.0.1) (2026-01-17)


### Bug Fixes

* update code from PR feedback ([3ca9da1](https://github.com/SpeedySpec/speedyspec-wp-hook-domain/commit/3ca9da13d1e5bc712a5ccc52e25911548b082b40))

## 1.0.0 (2026-01-17)


### Bug Fixes

* full domain codebase implementation ([b80b4b1](https://github.com/SpeedySpec/speedyspec-wp-hook-domain/commit/b80b4b1c495796bd506d4801b9bb2f895aa915b2))
* full implementation of wp hook domain ([030f95d](https://github.com/SpeedySpec/speedyspec-wp-hook-domain/commit/030f95d6c1eca64ad0cda9a31e8770c14a359552))


### Continuous Integration

* Add workflow for GitHub release and packagist updates ([59f548e](https://github.com/SpeedySpec/speedyspec-wp-hook-domain/commit/59f548e6ef0f1f3f32257d2a756af0610331fb19))
