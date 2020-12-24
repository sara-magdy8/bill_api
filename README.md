# Generate Bill API

PHP Rest API using CodeIgniter 4 Framework (MVC Design Pattern).

It accepts multiple products and provide the detailed bill for these products with the user requested currency.

This repository holds the whole source code for this API.

# How to Run

1) Place All Files on the root of your web server.
2) Open PHPunit.xml file you will find it on the root and change app.baseURL with your corresponding server URL.
Then read the follwing in order to know how to send a request to the API.

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
- "products" --- (Array) (Mandatory) : List with Product's Names we need to generate the bill for.
- "currency" --- (String) (Mandatory) : currency code to generate bill priceses in this currency.

Note: The API requires a basic authorization use (username: user , password: fvV2M4)

##### Request Example :

	{
	  "products" : ["T-shirt","T-shirt","Shoes","Jacket"],
	  "currency" : "USD"
	}

## API Response
The Response is in a **JSON** Format, With the appropriate HTTP status code.


##### Example :

	{
	    "Subtotal": "66.96 $",
	    "Taxes": "9.37 $",
	    "Discounts": " -- 10 % off Shoes: -2.499 $ -- 50 % off Jacket: -9.995 $",
	    "Total": "63.84 $"
	}

## Additional Notes:
- Logs Messages can be found in "/writable/logs" folder.
- Unit test implemented using PHPUnit, The testing files are located in "Tests" folder, to run these tests open the command line and go to the root directory and then type:

		 vendor\bin\PHPUnit

- If you want to add additional products or currencies to the APP, Open the **GenerateBill.php** file (app/Controllers/GenerateBill) and then add a new product object or a new currency object in **defineAppData** function.
