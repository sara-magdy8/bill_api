# Generate Bill API

PHP Rest API using CodeIgniter 4 Framework (MVC Design Pattern).

It accepts multiple products and provide the detailed bill for these products with the user requested currency.

This repository holds the whole source code for this API.

# How to Run

Place All Files on the root of your web server, And read the follwing in order to know how to send a request to the API.

## API Pre-defined products & Currencies
##### Products :
	- T-shirt (price: $10.99)
	- Pants (price: $14.99)
	- Jacket (price: $19.99)
	- Shoes (price: $24.99)
With the following offers : 

1) Shoes are on 10% off

2) Buy two t-shirts and get a jacket half its price.
	
##### Currencies :
	- Dollar (Base Currency) (Code: USD).
	- Egyptian Pound (Code: e£).
	- Euro (Code: €).
	
## API Request
Use **POST** Method. The request is in a JSON Format Containing the following data :
- "auth" --- (String) (Mandatory) : The Authentication code that must be provided to be able to access the API.
- "products" --- (Array) (Mandatory) : List with Product's Names we need to generate the bill for.
- "currency" --- (String) (Mandatory) : currency code to generate bill priceses in this currency.

##### Example :

	{
	  "auth" : "fvV2M4",
	  "products" : ["T-shirt","T-shirt","Shoes","Jacket"],
	  "currency" : "USD"
	}

## API Response
The Response is in a **JSON** Format
- "status" --- (String) : success/error.
- "response" --- (Array) : A List with the detailed bill fields **OR** the error message if error occurs.

##### Example :

	{
	    "status": "success",
	    "response": {
	        "Subtotal": "66.96 $",
	        "Taxes": "9.37 $",
	        "Discounts": " -- 10% off Shoes: -2.499 $ -- 50% off Jacket: -9.995 $",
	        "Total": "63.8404 $"
	    }
	}

## Additional Notes:
- You must provide **fvV2M4** as authentication code to the API.
- Logs Messages can be found in "/writable/logs" folder.
- Unit tests is located in "app/Controllers/Tests" folder and can be run by visit thses controllers from any broswer  
(ex. http://[Server_name]/tests/CurrencyTest/test_convert_amount).
- If you want to add additional products or currencies to the APP, Open the **Bills.php** file (app/Models/Bills) and add new product object or new currency object.
