# Create a custom JMS Serializer handler for mapping value

https://dev.to/elevado/create-a-custom-jms-serializer-handler-for-mapping-values-670

## Docker image

### Start docker image
```bash
docker compose -f docker-compose.yml up --build -d
```

### Run composer install
```bash
docker exec attribute_article composer install
```

### Run tests
```bash
docker exec attribute_article bin/phpunit
```

### Open bash

```bash
docker exec -it attribute_article bash
```

## Frontend

Open frontend: http://localhost:8080/
