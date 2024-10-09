# Create a custom JMS Serializer handler for mapping value

## A part of article Series: Mapping FieldValueIDs for the payload of the Emarsys API

https://dev.to/elevado/create-a-custom-jms-serializer-handler-for-mapping-values-670

## Docker image

### Start docker image
```bash
docker compose up -d
```

### Run composer install
```bash
docker exec article_jms_serializer composer install
```

### Run tests
```bash
docker exec article_jms_serializer bin/phpunit
```

### Open bash

```bash
docker exec -it article_jms_serializer bash
```

## Frontend

Open frontend: http://localhost:8080/
