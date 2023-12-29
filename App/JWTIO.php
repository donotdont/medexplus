<?php

namespace App;

require __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTIO
{

    private $privateKey = <<<EOD
    -----BEGIN RSA PRIVATE KEY-----
    MIIEowIBAAKCAQEAuz1ql9bEc59MwHzTYuDg+uievVo7lxxXYLysHxucHfsUwpYE
    GHovWJQbrK/At1Lc7FdZdq9asLVFM6cDB5kqVS/Kcg931dlcRw8Q678IsPG84bYe
    5a5obTwwYNSjoI7h5fa43eoEfWkEuBeW519uAZRmxX1OZ4iTPWZZr5PKDxUS5HfR
    6N9gutq93uyhoSBxbCwQ+pwgXPVEPa3vHy6yxGBl/Uk1A/g5Kuq/7DvtYNqOw8RW
    zNlGOR91DkDWF+/VxOftJ28A1pWe3/wnNGq1ISNeNYsuaqiVeyPFYa3bXxNebLe8
    IWGypGoCTdLG/i8zviWJ+jNn7EtWF/bccadEcQIDAQABAoIBACEFddUNdVwGzhxs
    Z0Na8ZKOj+BJF5VQ1EAlRh2+WswWydR/bH18nvj6Nd9Ap7YtEPVFemuqBU6AyZGQ
    dMJUStj3Mrbm73VxfgqyJ4PMDxZVq8AKxGwxVA1fvhB1r93TFRffaT8J1Hzhlol4
    744ikF4K9A0ESl2MApujf1GtuMbr3OsPrT/mS2SjJZOli1igcEihRyEg71v6hAjQ
    n8xw1WVUriIIemKhx8WMv58t9gdtSyjZ+2gzan/wjvDtsZkc64TdVW9/3rlZdnmm
    S1jVbCY/+qg4RsQV8N4ANGDeoBRJ6UdaUI4tOv2ECHUYaoExh+Y9hXY/ys58+PXw
    UrgxzAECgYEA8e+zByJgNx8yDE47jXmqt4aM8iUHQkBPp3lI2yUbraEuu4tTUOMs
    +vtVJtmIuPkMO+LVh9UW7KuMH8NbiVCLUcWsoztk78Dl+aaG2c2Ej/puDNHzK5wC
    VP1Ry9PxxwdLAAGoPRFG8a9IEOLsuEHr234xhuOcSx9x/Ar2WbtMg9ECgYEAxh/E
    6wiwQr+0sZeIer7JpryFviqWrEzj++9OKYbLyr+nbDCyD6YVelua+fR/cAATBHen
    nYbea2AOxekG0nQOV2SzPfFqMGvA7PaM+tlko6yp3m40aBCuvHCByVVSGMv6Nsma
    JvFSaS+gxQzKKJHpv9MJU36p3yF2tivOIPqP/qECgYAo3qQwnVcBmMx8la6rvJTh
    QeKHeUfboj+SfAOZuZAOab8aQXQGownTMCf3UyIslS3C5BPMSWwA+Q3Hw9mvvaJ7
    YthCDuODOOPgo4f+PSCareRvV/wrLsSaFNz/gMNEKvA5hQ4lmsiQRVr4N01j5wbv
    4kZ9ke+yJRK9UCDDkcdgMQKBgQC1LHQMYcOdJmV8SQ/a/wCz4o2C2rsyEJYi2jDl
    tIhDfYMCYn1R5hSZ8Y8Ep9qpdsftO+YhHSXjltGrlW5RoSNEM6fdKxhp8v9XMha1
    hXqMWeZ0qt5tk2PWmiN1EvqiBlVnKHA40FEGMzGXQBzVgKj6a2eJ3LhGfPlrmR9D
    9R0cQQKBgCN05AVHAmCT+LzNlEEVZe1xwaJ5IV5NNtOzamYliAhy5ZvhLLAz0OkQ
    zghUXCloc/7GKnKqMukvud4Zr+KS30KPGqIYUKfQBkVr/bWy2cpb9sdbLiW1B3nQ
    Ll1HsA/3I5d1o+NMo7w/WY6RklErfm4ekGaIcnzyt+zzABV25fMX
    -----END RSA PRIVATE KEY-----
    EOD;

    private $publicKey = <<<EOD
    -----BEGIN PUBLIC KEY-----
    MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuz1ql9bEc59MwHzTYuDg
    +uievVo7lxxXYLysHxucHfsUwpYEGHovWJQbrK/At1Lc7FdZdq9asLVFM6cDB5kq
    VS/Kcg931dlcRw8Q678IsPG84bYe5a5obTwwYNSjoI7h5fa43eoEfWkEuBeW519u
    AZRmxX1OZ4iTPWZZr5PKDxUS5HfR6N9gutq93uyhoSBxbCwQ+pwgXPVEPa3vHy6y
    xGBl/Uk1A/g5Kuq/7DvtYNqOw8RWzNlGOR91DkDWF+/VxOftJ28A1pWe3/wnNGq1
    ISNeNYsuaqiVeyPFYa3bXxNebLe8IWGypGoCTdLG/i8zviWJ+jNn7EtWF/bccadE
    cQIDAQAB
    -----END PUBLIC KEY-----
    EOD;

    public function Encode(array $payload)
    {
        return JWT::encode($payload, $this->privateKey, 'RS256');
        //echo "Encode:\n" . print_r($jwt, true) . "\n";
    }


    public function Decode(string $jwt)
    {
        return (array)JWT::decode($jwt, new Key($this->publicKey, 'RS256'));
        //echo "Decode:\n" . print_r($decoded_array, true) . "\n";
    }
}
