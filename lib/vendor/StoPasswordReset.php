<?php
/**
 * This class helps building secure password-reset links, and can verify
 * them. It generates unpredictable tokens, and provides hash-values to
 * store in the database. The same class can be used to build other types
 * of token-links, like registration-links.
 *
 * @example
 * /// Send password reset link
 * $userId = findUserByEmail($_POST['email']);
 * if (!is_null($userId))
 * {
 *   // Generate a new token with its hash
 *   StoPasswordReset::generateToken($tokenForLink, $tokenHashForDatabase);
 *   $emailLink = 'https://www.example.com/reset_password.php?tok=' . $tokenForLink;
 *   $creationDate = new DateTime();
 *   savePasswordResetToDatabase($tokenHashForDatabase, $userId, $creationDate);
 *   sendPasswordResetEmail($emailLink);
 * }
 * 
 * /// Reset password
 * if (!isset($_GET['tok']) || !StoPasswordReset::isTokenValid($_GET['tok']))
 *   handleErrorAndExit('The token is invalid.');
 * // Search for the token hash in the database
 * $tokenHashFromLink = StoPasswordReset::calculateTokenHash($_GET['tok']);
 * if (!loadPasswordResetFromDatabase($tokenHashFromLink, $userId, $creationDate))
 *   handleErrorAndExit('The token does not exist or has already been used.');
 * // Check whether the token has expired
 * if (StoPasswordReset::isTokenExpired($creationDate))
 *   handleErrorAndExit('The token has expired.');
 * letUserChangePassword($userId);
 *
 * @author Martin Stoeckli - www.martinstoeckli.ch/php
 * @copyright Martin Stoeckli 2013, this code may be freely used in every
 *   type of project, it is provided without warranties of any kind.
 * @version 2.1
 */
class StoPasswordReset
{
	// The token must be strong enough, so we can store its unsalted hash
	// with a fast algorithm (sha256) in the database. Do not go beneath 20.
	protected static $tokenLength = 24;

	// Tokens expires after 8 hours, choose whatever you want.
	protected static $expiryInterval = 'PT8H';
	
	/**
	 * Generates a new token, that can be used to build a password reset link.
	 * Like a password, the token itself should not be stored in the database,
	 * rather store its hash-value.
	 * @param string $tokenForLink This variable receives the new generated
	 *   random token. It will be used to build the password reset link.
	 * @param string $tokenHashForDatabase This variable receives the
	 *   hash-value of the token. It can be safely stored in the database
	 *   and is always 64 characters in length.
	 */
	public static function generateToken(&$tokenForLink, &$tokenHashForDatabase)
	{
		$tokenForLink = StoPasswordReset::generateRandomBase62String(
			StoPasswordReset::$tokenLength);
		$tokenHashForDatabase = StoPasswordReset::calculateTokenHash($tokenForLink);
	}
	
	/**
	 * Calculates the hash of a token. This hash can be searched for in the
	 * database, after the user pressed a link containing a token.
	 * @param string $token Token that was extracted from the clicked link.
	 * @throws Exception It the token is invalid.
	 * @return string Returns the searchable hash-value of the token.
	 */
	public static function calculateTokenHash($token)
	{
		if (strlen($token) < 20)
			throw new Exception('The token is too short and therefore too weak');
		
		return hash('sha256', $token, false);
	}
	
	/**
	 * Makes a formal test whether a token is valid.
	 * @param string $token Token to test.
	 * @return bool Returns true if the token is formally valid,
	 *   otherwise false.
	 */
	public static function isTokenValid($token)
	{
		// Valid tokens must be of a certain length and must contain
		// only following characters 0..9, a..z, A..Z.
		return !is_null($token) &&
			(StoPasswordReset::$tokenLength == strlen($token)) &&
			ctype_alnum($token);
	}
	
	/**
	 * Checks whether the token is expired.
	 * @param DateTime $creationDate The moment the token was created.
	 * @return bool Returns true if the token is expired, otherwise false.
	 */
	public static function isTokenExpired(DateTime $creationDate)
	{
		$now = new DateTime();

		$expiryDate = clone $creationDate;
		$validFor = new DateInterval(StoPasswordReset::$expiryInterval);
		$expiryDate->add($validFor);

		return $now > $expiryDate;
	}
	
	/**
	 * Generates a random string of a given length, using the random source of
	 * the operating system. The string contains only safe characters of this
	 * alphabet: 0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz
	 * @param int $length Number of characters the string should have.
	 * @return string A random base62 encoded string.
	 */
	public static function generateRandomBase62String($length)
	{
		if (!defined('MCRYPT_DEV_URANDOM')) die('The MCRYPT_DEV_URANDOM source is required (PHP 5.3).');
		$result = '';
		$remainingLength = $length;
		do
		{
			// We take advantage of the fast base64 encoding
			$binaryLength = (int)($remainingLength * 3 / 4 + 1);
			$binaryString = mcrypt_create_iv($binaryLength, MCRYPT_DEV_URANDOM);
			$base64String = base64_encode($binaryString);
			
			// Remove invalid characters
			$base62String = str_replace(array('+', '/', '='), '', $base64String);
			$result .= $base62String;
			
			// If too many characters have been removed, we repeat the procedure
			$remainingLength = $length - strlen($result);
		} while ($remainingLength > 0);
		return substr($result, 0, $length);
	}
}

?>
