### Items and properties ###

In order to make api requests, you will need an access token.

It should be provided as HTTP header named "X-API-KEY".

All possible requests and their parameters can be viewed using "/api" endpoint.

You can find examples with sample JSON data below.


1. Retrieve the collection of Items

curl -X 'GET' \
  'https://127.0.0.1:8000/api/items?page=1' \
  -H 'accept: application/ld+json'

Success response code: 200

Sample response:
{
  "@context": "/api/contexts/Item",
  "@id": "/api/items",
  "@type": "hydra:Collection",
  "hydra:member": [
    {
     {
      "@id": "/api/items/1",
      "@type": "Item",
      "name": "Pizza",
      "properties": [
        {
          "@id": "/api/properties/121",
          "@type": "Property",
          "value": "Price: 2.00 EUR"
        }
      ]
    }
    }
   ],
  "hydra:totalItems": 1
}


2. Retrieve single Item by ID

curl -X 'GET' \
  'https://127.0.0.1:8000/api/items/1' \
  -H 'accept: application/ld+json'

Success response code: 200

Sample response:
{
  "@context": "/api/contexts/Item",
  "@id": "/api/items/1",
  "@type": "Item",
  "name": "Ice",
  "properties": [
    {
      "@id": "/api/properties/121",
      "@type": "Property",
      "value": "Price: 2.00 EUR"
    }
  ]
}


3. Create a new Item (with name "Pizza"):

curl -X 'POST' \
  'https://127.0.0.1:8000/api/items' \
  -H 'accept: application/ld+json' \
  -H 'Content-Type: application/ld+json' \
  -d '{
  "name": "Pizza"
}'

Success response code: 201

Sample response:
{
  "@context": "/api/contexts/Item",
  "@id": "/api/items/1",
  "@type": "Item",
  "name": "Ice",
  "properties": []
}

Response body contains "@id" or IRI (Internationalized Resource Identifier) which
is used in other requests to identify Item which you want to modify ("/api/items/1" here).


4. Update the Item name by ID (change name to "Bread"):

curl -X 'PUT' \
  'https://127.0.0.1:8000/api/items/1' \
  -H 'accept: application/ld+json' \
  -H 'Content-Type: application/ld+json' \
  -d '{
  "name": "Bread"
}'

Success response code: 200

Sample response:
{
  "@context": "/api/contexts/Item",
  "@id": "/api/items/1",
  "@type": "Item",
  "name": "Bread",
  "properties": []
}


5. Delete the Item with its Properties:

curl -X 'DELETE' \
  'https://127.0.0.1:8000/api/items/1' \
  -H 'accept: */*'

Success response code: 204


6. Create a new Property for an Item ("Price: 2.00 EUR"):

curl -X 'POST' \
  'https://127.0.0.1:8000/api/properties' \
  -H 'accept: application/ld+json' \
  -H 'Content-Type: application/ld+json' \
  -d '{
  "value": "Price: 2.00 EUR",
  "item": "/api/items/1"
}'

Success response code: 201

Sample response:

{
  "@context": "/api/contexts/Property",
  "@id": "/api/properties/121",
  "@type": "Property",
  "id": 121,
  "value": "Price: 2.00 EUR",
  "item": "/api/items/1"
}
