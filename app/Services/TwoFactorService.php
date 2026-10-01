<?php

namespace App\Services;

class TwoFactorService
{
    private static string $base32Alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    /**
     * Generate a 16-character Base32 secret key.
     */
    public static function generateSecretKey(): string
    {
        $secret = '';
        $bytes = random_bytes(10);
        for ($i = 0; $i < 16; $i++) {
            $secret .= self::$base32Alphabet[ord($bytes[$i % 10]) % 32];
        }
        return $secret;
    }

    /**
     * Generate standard otpauth URI.
     */
    public static function getOtpAuthUri(string $email, string $secret, string $issuer = 'Software Tech'): string
    {
        $cleanSecret = strtoupper(str_replace(' ', '', $secret));
        return 'otpauth://totp/' . rawurlencode($issuer) . ':' . rawurlencode($email)
            . '?secret=' . $cleanSecret
            . '&issuer=' . rawurlencode($issuer)
            . '&algorithm=SHA1&digits=6&period=30';
    }

    /**
     * Generate QR Code image URL.
     */
    public static function getQrCodeUrl(string $email, string $secret, string $issuer = 'Software Tech', int $size = 150): string
    {
        $otpauth = self::getOtpAuthUri($email, $secret, $issuer);
        return 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size . '&margin=4&data=' . rawurlencode($otpauth);
    }

    /**
     * Verify a 6-digit TOTP code against a Base32 secret key (RFC 6238).
     */
    public static function verifyCode(?string $secret, ?string $code, int $window = 1): bool
    {
        if (empty($secret) || empty($code)) {
            return false;
        }

        $code = trim((string) $code);
        if (strlen($code) !== 6 || !ctype_digit($code)) {
            return false;
        }

        $secret = strtoupper(str_replace(' ', '', $secret));
        $binaryString = '';
        for ($i = 0; $i < strlen($secret); $i++) {
            $pos = strpos(self::$base32Alphabet, $secret[$i]);
            if ($pos === false) {
                continue;
            }
            $binaryString .= sprintf('%05b', $pos);
        }

        $binaryLength = strlen($binaryString);
        $secretBytes = '';
        for ($i = 0; $i + 8 <= $binaryLength; $i += 8) {
            $secretBytes .= chr(bindec(substr($binaryString, $i, 8)));
        }

        if (empty($secretBytes)) {
            return false;
        }

        $timeSlice = floor(time() / 30);

        for ($offset = -$window; $offset <= $window; $offset++) {
            $slice = (int) ($timeSlice + $offset);
            $packedTime = pack('N*', 0) . pack('N*', $slice);
            $hmac = hash_hmac('sha1', $packedTime, $secretBytes, true);
            $offsetByte = ord(substr($hmac, -1)) & 0x0F;
            $hashPart = substr($hmac, $offsetByte, 4);
            $value = unpack('N', $hashPart)[1] & 0x7FFFFFFF;
            $calculatedCode = str_pad((string) ($value % 1000000), 6, '0', STR_PAD_LEFT);

            if (hash_equals($calculatedCode, $code)) {
                return true;
            }
        }

        return false;
    }
}
