# Weekly Food Menu

```
GET /v2/foodservices/{year}/{week}/menu.{format}
```

## Description

> This method returns the given week and year's food menu.

## Summary

<table>
  <tr>
    <td><b>Name</b></td>
    <td><b>Value</b></td>
    <td><b><b>Name</b></b></td>
    <td><b>Value</b></td>
  </tr>
  <tr>
    <td><b>Request Protocol</b></td>
    <td>GET</td>
    <td><b>Requires API Key</b></td>
    <td>Yes</td>
  </tr>
  <tr>
    <td><b>Method ID</b></td>
    <td>1153</td>
    <td><b>Enabled</b></td>
    <td>Yes</td>
  </tr>
  <tr>
    <td><b>Service Name</b></td>
    <td>foodservices</td>
    <td><b>Service ID</b></td>
    <td>227</td>
  </tr>
  <tr>
    <td><b>Information Steward</b></td>
    <td>UW FoodServices</td>
    <td><b>Data Type</b></td>
    <td>Direct DB Connection</td>
  </tr>
  <tr>
    <td><b>Update Frequency</b></td>
    <td>Every request (live)</td>
    <td><b>Cache Time</b></td>
    <td>0 seconds</td>
  </tr>
</table>


### Notes

- Usage won't increase if there is not data returned
- We cannot modify the data from this method


### Sources

- http://foodservices.uwaterloo.ca


## Parameters

```
GET /v2/foodservices/{year}/{week}/menu.{format}
```

### Input

- **year** - The year of menu to be requested  *(required)*
- **week** - The week number of the menu to be requested  *(required)*
- **format** - The format of the output  *(required)*
  - json
  - xml


### Filter

- **key** - Your API key  *(required)*
- **callback** - JSONP callback format  *(optional)*


## Examples

```
GET /v2/foodservices/{year}/{week}/menu.{format}
```

- **http://api.uwaterloo.ca/public/v2/foodservices/2013/12/menu.json**
- **http://api.uwaterloo.ca/public/v2/foodservices/2013/22/menu.xml**
- **http://api.uwaterloo.ca/public/v2/foodservices/2013/15/menu.json?callback=myResponse**


## Response

<table>
  <tr>
    <td><b>Field Name</b></td>
    <td><b>Type</b></td>
    <td><b>Value Description</b></td>
  </tr>
  <tr>
    <td><b>date</b></td>
    <td>object</td>
    <td>Menu date object<br><table>
  <tr>
    <td><b>week</b></td>
    <td>integer</td>
    <td>Requested week</td>
  </tr>
  <tr>
    <td><b>year</b></td>
    <td>integer</td>
    <td>Requested year</td>
  </tr>
  <tr>
    <td><b>start</b></td>
    <td>string</td>
    <td>Starting day of the menu (Y-m-d)</td>
  </tr>
  <tr>
    <td><b>end</b></td>
    <td>string</td>
    <td>Ending day of the menu (Y-m-d)</td>
  </tr>
</table>
</td>
  </tr>
  <tr>
    <td><b>outlets</b></td>
    <td>list</td>
    <td>Available outlets<br><table>
  <tr>
    <td><b>outlet_name</b></td>
    <td>string</td>
    <td>Name of the outlet</td>
  </tr>
  <tr>
    <td><b>outlet_id</b></td>
    <td>integer</td>
    <td>Foodservices ID for the outlet</td>
  </tr>
  <tr>
    <td><b>menu</b></td>
    <td>list</td>
    <td>The outlet menu list<br><table>
  <tr>
    <td><b>date</b></td>
    <td>string</td>
    <td>Date of the menu (Y-m-d)</td>
  </tr>
  <tr>
    <td><b>day</b></td>
    <td>string</td>
    <td>Day of the week</td>
  </tr>
  <tr>
    <td><b>meals</b></td>
    <td>object</td>
    <td>All the meals for the day<br><table>
  <tr>
    <td><b>lunch</b></td>
    <td>list</td>
    <td>Lunch menu items<br><table>
  <tr>
    <td><b>product_name</b></td>
    <td>string</td>
  </tr>
  <tr>
    <td><b>diet_type</b></td>
    <td>string</td>
  </tr>
  <tr>
    <td><b>product_id</b></td>
    <td>integer</td>
  </tr>
</table>
</td>
  </tr>
  <tr>
    <td><b>dinner</b></td>
    <td>list</td>
    <td>Dinner menu<br><table>
  <tr>
    <td><b>product_name</b></td>
    <td>string</td>
  </tr>
  <tr>
    <td><b>diet_type</b></td>
    <td>string</td>
  </tr>
  <tr>
    <td><b>product_id</b></td>
    <td>integer</td>
  </tr>
</table>
</td>
  </tr>
  <tr>
    <td><b>notes</b></td>
    <td>string</td>
    <td>Additional announcements for the day</td>
  </tr>
</table>
</td>
  </tr>
</table>
</td>
  </tr>
</table>
</td>
  </tr>
</table>


# Output

#### JSON

```json
{
  "meta":{
    "requests":-1,
    "timestamp":1370802917,
    "status":511,
    "message":"API key is required (?key=)",
    "method_id":1153,
    "version":2.05,
    "method":{
      
    }
  },
  "data":{
    
  }
}
```

#### XML

```xml
<?xml version="1.0" encoding="UTF-8"?>
<response>
  <meta>
    <requests>-1</requests>
    <timestamp>1370802918</timestamp>
    <status>511</status>
    <message>API key is required (?key=)</message>
    <method_id>1153</method_id>
    <version>2.05</version>
    <method></method>
  </meta>
  <data></data>
</response>
```

