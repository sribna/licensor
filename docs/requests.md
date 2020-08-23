# Requests

## Customer requests
Requests sent by licensees

### Key activation

**Endpoint:** `/key/activate`

**Method:** `POST`

**Body:** String, base64 encoded private key

**Success response:**

    Code: 200
    Content: {success : "Response has been sent"}
    
**Error response:**

    Code: 400 OR 500
    Content: {error : "Some error message"}
    
### Key verification

**Endpoint:** `/key/check`

**Method:** `POST`

**Body:** String, base64 encoded private key

**Success response:**

    Code: 200
    Content: {success : "Response has been sent"}
    
**Error response:**

    Code: 400 OR 500
    Content: {error : "Some error message"}

## Callback requests
Requests sent by the licensor to a licensee

**Endpoint:** `<key domain>/key/callback`

**Method:** `POST`

**Body:** String, base64 encoded private key

**Success response:**

    Code: 200
    
Note: All the above endpoints can be adjusted in `config/licensor.php`

