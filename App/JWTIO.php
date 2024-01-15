<?php

namespace App;

require __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTIO
{

    private $privateKey = <<<EOD
    -----BEGIN PRIVATE KEY-----
    MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDczodVgfFC95Or
    tW1uuRKBskHyrpYGWdl9aVwOaNthBAd+3Xh9h9LrFJdap3lCsLZtGe1tBahF9Dtn
    ODn+YWJ240VWGTFEQaPrTIGGsUEVBDzTdjulqPMf0rLRDO5i2V4umN+i6U2jfhwi
    BywhpjZ4p2iPDDKVMOIG5pXO7FBmrbJD9TFK1nFdpXMZkSp8f3u1o6eGpWBRsKi5
    Ik0BftTobgnPrCn0OZ2lR90/vGLQRS5xBr9ShLQtweZJ8MUS8uXBv8ZqZaSZXDoA
    ivNpD7xpyzzEq3Lfn91f168GDCbm8ILWRuSg7fTUR+zVCVuEAdKD3jV2krfSpugM
    ZJ8O+GF9AgMBAAECggEAWpxuMQrIBOEwOlCTfdJ447xRD9PRQP3yIGLyIf+ptyA+
    KzObVHg+d7XVfqAnRNTbp93x7sFZkottLSiHs/omu/773x8rdoUxTUfyB2IKASPi
    Ci0EwTCZxL9kHiGeWfY1jg2yCFqRP7xqsxQaahyZUd3xLHKm904+EvLhGy8it19g
    5DAsvDDBD55tGXYV86z18CEjEplXrWsFqL9x7WAhtw/SpD53r3w2MYMcG0MOMrQQ
    khBaNu198LCPOO81Y7IgEbW4xtxD/1yCuiIvXghVDOuAJ4AMXvjJWeGPg0kmm2ii
    B1WQTkCCE7Dxd+ZUO/26DvEBIB360/cYv1Kv/waGyQKBgQD5RTOBVXFlCDX2gq6y
    mMuA0npbIgVmNZ0TcLYrhhGWj5GfhacS3lSBFuGguwUhnappu2lNL8trRif97bBU
    WwI5oWBCo3HvLH7HM1cdsnEBVxNwVdINjOYI79Jea7DvEEVBYCDhtesVjuwheJFE
    m7iLdh9+upFpEUz3O9L9L11SHwKBgQDixJr0s0M5rZDfOYzwACfdv9EuM6fun2k0
    WFiv1pkFFuF2h52Glv2Y2yWgRb9Bcoy5uTRRJnBcs8G8QTuthC5lFE4g8me3XuLz
    Ekz4JobKQNdQlU2t53m6EGK9FzzCQaFJCfEqiYfKTvtgXH1081j20ovwio29Ykcb
    CU5+KVVw4wKBgEKjLVG19cp8l3B/HxS/pCecHPmf7rGUQ5me2BRqkukdGGD86d0x
    coXQCCFf3c9Oxu/dGhl4XXkgGDDaCfmDGuUtLv6hkyPNUVcPcoqN2YfyL1AooS7e
    K+DXu/Vh9Lr0Lo97k07Dw0TQIHASO0OMHZmqymCmn5jm9xxg8dadguUhAoGBAJ0n
    N2Qh35LXUDEDB0NEOFybFHJ6ZgUfAJ/AIiYqCH3Yb4PfVDHz7efC5/58l6DZq+EC
    EtmyU+hKwxad9qe3lReDzOlUVMBlx85Afory2DLOYOl9rwm4A0oJFQSKWBcfuJCP
    jUy3Zx2zQs5zCpbEFoFnRIf83WHD/Bcmifkw1/+VAoGBAMuS6WmUkUKArQqUXDX4
    mS+T/WfgrqkFRi7a0d+aMkHKCxzs2nIbF17b2bf9ZdnomDAbwwbjAI0jqSoHBReL
    v7w/7vuWVmVhonMxkTjm8TL9gUmHcHGrFf7356wXJrNoewOVDfJ5EMAyFjb7fWFj
    sPWB0lwwITGqnMSucDcXMfrf
    -----END PRIVATE KEY-----
    EOD;

    private $publicKey = <<<EOD
    -----BEGIN PUBLIC KEY-----
    MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA3M6HVYHxQveTq7VtbrkS
    gbJB8q6WBlnZfWlcDmjbYQQHft14fYfS6xSXWqd5QrC2bRntbQWoRfQ7Zzg5/mFi
    duNFVhkxREGj60yBhrFBFQQ803Y7pajzH9Ky0QzuYtleLpjfoulNo34cIgcsIaY2
    eKdojwwylTDiBuaVzuxQZq2yQ/UxStZxXaVzGZEqfH97taOnhqVgUbCouSJNAX7U
    6G4Jz6wp9DmdpUfdP7xi0EUucQa/UoS0LcHmSfDFEvLlwb/GamWkmVw6AIrzaQ+8
    acs8xKty35/dX9evBgwm5vCC1kbkoO301Efs1QlbhAHSg941dpK30qboDGSfDvhh
    fQIDAQAB
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
