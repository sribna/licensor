# Requests

## Requests sent by licensees

### Key activation

**Endpoint:** `/key/activate`

**Method:** `POST`

**Body:** base64 encoded hash

**Success response:**

    Code: 200
    Content: {"success" : "Callback URL path"}
    
**Error response:**

    Code: 4xx OR 5xx
    Content: {"error" : "Some error message"}
    
### Key verification

**Endpoint:** `/key/check`

**Method:** `POST`

**Body:** base64 encoded hash

**Success response:**

    Code: 200
    Content: {"success" : "Callback URL path"}
    
**Error response:**

    Code: 4xx OR 5xx
    Content: {"error" : "Some error message"}

## Requests sent to licensees

### Private key callback
Note: Endpoint path can be adjusted by the licensee in the request header

**Endpoint:** `<key domain>/key/callback`

**Method:** `POST`

**Body:** String, base64 encoded private key

**Success response:**

    Code: 200
    
All the above endpoints can be adjusted in `config/licensor.php`

