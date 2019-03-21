# User login message api   

| attribute | value |
|-----------|-------|
| version   | 1.0   |
| creator   | ket2.nguyen.huu@gmail.com |
| created   | 2019-03-20 |
| updater   | 
| updated   |  |

## 1. Overview 

- A API allow user login to system by SNS.
- Currently, user is not allowed register by anyway.

## 2. Endpoint

- */api/v1/user_login_api*

## 3. Method

- POST

## 4.Input 

name  | description| format | type | range | required
--- | ---| ---| ---|---|---
email|email of user|-|string|-|true 
name|username|-|string|-|true
password|password|-|string|-|true
sns_account_id|sns_account_id|-|string|-|true
social_type|1:facebook, 2:twitter, 3:instagram, 4:youtube|-|int|from 1 to 4|true
sns_access_token|access token of sns|-|string|-|true
link|link to sns page|-|string|-|true

## 5.Example API Call

- Method : POST

- Header: X-Requested-With: XMLHttpRequest

- Body: 
    - POST param
        - email : 'abcd_xyz@gmail.com',
        - name: 'kelvin',
        - password: '',
        - sns_account_id: '89625808',
        - social_type: '2',
        - sns_access_token: '3xyzljfdsajldsjaf2354%fdajasdf.fdaljkfda',
        - link: 'https://twitter.com/Cuong_dep_trai89625808'
        
- Url : *http://domain_name/api/v1/user_login_api/*

## 6. Diagram 

- N/A

## 7. Action

- Step 1 : Validate input parameter
    + If not valid, return error message corresponding to each of parameter
    + If valid, go to step 2          
    ↓
    
- Step 2 : Check if user email exists on DB
   + Yes: get that user from DB and return on json respond with existed jwt
   + No: go to step 3
 
    
- Step 3 : Create new user base on input information

- Step 4 : Generate jwt access token for new user 

- Step 5 : return json respond with user info and jwt access token

## 8. Output

- Json message is sent to client  

## 9. Example Response 

- HTTP Code : 200

- JSON response 
    
    + Success:
    
    ```
    {
        'status': 1, 
    }
    ```
    
    + Failed: 
    
    ```
    {
        'status': 0,
        'message': Error message 
    }
    ```

## 10. Exception

- Return error message if parameter is not valid 