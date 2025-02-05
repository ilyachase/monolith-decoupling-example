This is an example repository for my [Medium article](https://medium.com/@ilyachase/practical-example-of-decoupling-a-monolithic-php-application-6ff82fefc80a) on how to actually decouple a monolith in PHP.

There are multiple branches reflecting the steps needed:
- [step2-split-databases](https://github.com/ilyachase/monolith-decoupling-example/tree/step2-splt-databases)
- [step3-enforcing-modules-boundaries](https://github.com/ilyachase/monolith-decoupling-example/tree/step3-enforcing-modules-boundaries)
- [step4-services](https://github.com/ilyachase/monolith-decoupling-example/tree/step4-services)
- [step5-event-driven](https://github.com/ilyachase/monolith-decoupling-example/tree/step5-event-driven)

The main use case of this repository is to see code examples as you read through the article, but if you decided to run it locally, do the following:
```
docker-compose up -d
make vendor
make db
make migration
make fixture
```
