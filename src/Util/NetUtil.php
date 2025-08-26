<?php
declare(strict_types=1);

namespace Cupcake\Util;

class NetUtil
{
    /**
     * Validate if the given string is a valid IP address (IPv4 or IPv6).
     *
     * @param string $ip The IP address to validate.
     * @return bool True if valid, false otherwise.
     */
    public static function isValidIp(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Validate if the given string is a valid CIDR notation.
     * CIDR can be either IPv4 or IPv6.
     *
     * @param string $cidr The CIDR notation to validate
     * @return bool True if valid, false otherwise.
     */
    public static function isValidCidr(string $cidr): bool
    {
        $parts = explode('/', $cidr);
        if (count($parts) !== 2) {
            return false;
        }

        $ip = $parts[0];
        $mask = (int)$parts[1];

        if (!self::isValidIp($ip)) {
            return false;
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return $mask >= 0 && $mask <= 32;
        } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return $mask >= 0 && $mask <= 128;
        }

        return false;
    }

    /**
     * Validate if the given string is a valid IPv4 address.
     *
     * @param string $ip The IP address to validate.
     * @return bool True if valid, false otherwise.
     */
    public static function isIpv4(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
    }

    /**
     * Validate if the given string is a valid IPv6 address.
     *
     * @param string $ip The IP address to validate.
     * @return bool True if valid, false otherwise.
     */
    public static function isIpv6(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
    }

    /**
     * Check if an IP address (IPv4 or IPv6) is within a given CIDR range.
     *
     * @param string $ip The IP address to check.
     * @param string $cidr The CIDR range
     * @return bool True if the IP is within the CIDR range, false otherwise.
     */
    public static function isIpInCidr(string $ip, string $cidr): bool
    {
        if (self::isIpv4($ip)) {
            return self::ipv4InCidr($ip, $cidr);
        } elseif (self::isIpv6($ip)) {
            return self::ipv6InCidr($ip, $cidr);
        }

        return false;
    }

    /**
     * Check if an IPv4 address is within a given CIDR range.
     *
     * @param string $ip The IPv4 address to check.
     * @param string $cidr The CIDR range (e.g., '192.168.0.0/8').
     * @return bool True if the IP is within the CIDR range, false otherwise.
     */
    public static function ipv4InCidr(string $ip, string $cidr): bool
    {
        if (!self::isIpv4($ip) || !self::isValidCidr($cidr)) {
            return false;
        }

        [$subnet, $mask] = explode('/', $cidr);
        if (!self::isIpv4($subnet)) {
            return false;
        }

        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);
        $maskLong = -1 << 32 - (int)$mask;
        $subnetLong &= $maskLong; // nb: in case the supplied subnet wasn't correctly aligned

        return ($ipLong & $maskLong) === $subnetLong;
    }

    /**
     * Check if an IPv6 address is within a given CIDR range.
     *
     * @param string $ip The IPv6 address to check.
     * @param string $cidr The CIDR range (e.g., '2001:0db8::/32').
     * @return bool True if the IP is within the CIDR range, false otherwise.
     */
    public static function ipv6InCidr(string $ip, string $cidr): bool
    {
        if (!self::isIpv6($ip) || !self::isValidCidr($cidr)) {
            return false;
        }

        [$subnet, $mask] = explode('/', $cidr);
        if (!self::isIpv6($subnet)) {
            return false;
        }

        $ipBin = inet_pton($ip);
        $subnetBin = inet_pton($subnet);
        if ($ipBin === false || $subnetBin === false) {
            return false;
        }

        $ipBits = unpack('A16', $ipBin)[1];
        $subnetBits = unpack('A16', $subnetBin)[1];

        $fullBytes = (int)($mask / 8);
        $remainingBits = $mask % 8;

        if (strncmp($ipBits, $subnetBits, $fullBytes) !== 0) {
            return false;
        }

        if ($remainingBits > 0) {
            $maskByte = (0xFF << 8 - $remainingBits) & 0xFF;
            if ((ord($ipBits[$fullBytes]) & $maskByte) !== (ord($subnetBits[$fullBytes]) & $maskByte)) {
                return false;
            }
        }

        return true;
    }
}
