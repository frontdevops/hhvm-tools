<?php

$pidfile = ini_get('hhvm.pid_file');
if (!file_exists($pidfile)) $pidfile = ini_get('pid');

$string = (object) [
	'unknown' => '<i>unknown</i>'
];

if ($pidfile) {

	$mtime	= @filemtime($pidfile);
	$pid	= @file_get_contents($pidfile);
	$cmdline= @file_get_contents("/proc/$pid/cmdline");

	$uptime = $pidfile && $mtime
		? (new DateTime("@$mtime"))
			->diff(new DateTime('NOW'))
			->format('%a days, %h hours, %i minutes')
		: $string->unknown;

	if (function_exists('php_ini_loaded_file')) $inifile = php_ini_loaded_file();

	if (!$inifile && $cmdline) {
		$inifile = implode(
				', ',
				array_filter(
					preg_split('~--?(c|user|mode)~is', $cmdline),
					function($item){ if (preg_match('~\.ini~i', $item)) return $item; }
				)
			);

	}

} else {
	$uptime =
	$inifile = $string->unknown;
}

?><!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<style type="text/css">
		body {
			background-color: #fff;
			color: #222;
			font-family: sans-serif;
		}

		pre {
			margin: 0;
			font-family: monospace;
		}

		a:link {
			color: #009;
			text-decoration: none;
			background-color: #fff;
		}

		a:hover {
			text-decoration: underline;
		}

		table {
			border-collapse: collapse;
			border: 0;
			width: 934px;
			box-shadow: 1px 2px 3px #ccc;
		}

		.center {
			text-align: center;
		}

		.center table {
			margin: 1em auto;
			text-align: left;
		}

		.center th {
			text-align: center !important;
		}

		td, th {
			border: 1px solid #666;
			font-size: 75%;
			vertical-align: baseline;
			padding: 4px 5px;
		}

		h1 {
			font-size: 150%;
		}

		h2 {
			font-size: 125%;
		}

		.p {
			text-align: left;
		}

		.e {
			background-color: #ccf;
			width: 300px;
			font-weight: bold;
		}

		.h {
			background-color: #99c;
			font-weight: bold;
		}

		.v {
			background-color: #ddd;
			max-width: 300px;
			overflow-x: auto;
		}

		.v i {
			color: #999;
		}

		img {
			float: right;
			border: 0;
		}

		hr {
			width: 934px;
			background-color: #ccc;
			border: 0;
			height: 1px;
		}

		.hhvm {
			background-color: #000;
			color: #fff;
		}

		.hhvm img {
			height: 67px;
		}
	</style>
	<title>phpinfo()</title>
	<meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE"/>
</head>
<body>
<div class="center">
	<table>
		<tr class="h hhvm">
			<td>
				<a href="http://hhvm.com/"><img border="0" alt="HHVM logo" src="data:image/png;base64,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"/></a>
				<h1 class="p">HHVM Version <?= ini_get('hphp.compiler_version') ?></h1>
				<p>Compiler ID <?= ini_get('hphp.compiler_id') ?></p>
			</td>
		</tr>
	</table>
	<table>
		<tr class="h">
			<td>
				<a href="http://www.php.net/"><img border="0" alt="PHP logo" src="data:image/png;base64,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"/></a>
				<h1 class="p">PHP Version <?= phpversion() ?></h1>
			</td>
		</tr>
	</table>
	<table>
		<tr>
			<td class="e">System</td>
			<td class="v"><?= php_uname() ?></td>
		</tr>
		<tr>
			<td class="e">Uptime</td>
			<td class="v"><?= $uptime ?></td>
		</tr>
		<tr>
			<td class="e">Configure Command</td>
			<td class="v"></td>
		</tr>
		<tr>
			<td class="e">Server API</td>
			<td class="v"><?= strtoupper(php_sapi_name()) . '/' . ucfirst(ini_get('hhvm.server.type')) ?></td>
		</tr>
		<tr>
			<td class="e">Virtual Directory Support</td>
			<td class="v">disabled</td>
		</tr>
		<tr>
			<td class="e">Configuration File (php.ini) Path</td>
			<td class="v"></td>
		</tr>
		<tr>
			<td class="e">Loaded Configuration File</td>
			<td class="v"><?= $inifile ?></td>
		</tr>
		<tr>
			<td class="e">Scan this dir for additional .ini files</td>
			<td class="v"></td>
		</tr>
		<tr>
			<td class="e">Additional .ini files parsed</td>
			<td class="v">(none)</td>
		</tr>
		<tr>
			<td class="e">PHP API</td>
			<td class="v"></td>
		</tr>
		<tr>
			<td class="e">Registered PHP Streams</td>
			<td class="v"><?= implode(', ', stream_get_wrappers()) ?></td>
		</tr>
	</table>
	<table>
		<tr class="v">
			<td>
				<a href="http://hacklang.org/"><img border="0" height="90" alt="Hacklang logo"
													src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAACWCAYAAABkW7XSAAABL2lDQ1BJQ0MgUHJvZmlsZQAAKBVjYGAycHRxcmUSYGDIzSspCnJ3UoiIjFJgv8DAwcDNIMxgzGCdmFxc4BgQ4MMABHn5eakgGhV8u8bACBK5rAsyC1WOII8ruaCoBKjqDxAbpaQWJzMwMBoA2dnlJQVAccY5QLZIUjaYvQHELgoJcgaKHwGy+dIh7CsgdhKE/QTELgJ6AqjmC0h9OpjNxAFiJ0HYMiB2SWoFyF4G5/yCyqLM9IwSBSMDAwMFx5T8pFSF4MriktTcYgXPvOT8ooL8osSS1BSgWoj7QLoYBCEKQSGmYWhpaaEJFqUiAYoHiHGfA8Hhyyh2BiGGsCi5tKgMymNkMmZgIMRHmDFHgoHBfykDA8sfhJhJLwPDAh0GBv6pCDE1QwYGAX0Ghn1zAMOvUG/sJay0AAAACXBIWXMAAA9hAAAPYQGoP6dpAAARUklEQVR4Ae2decwdUx/HT7WoKhVqacVSRYNSaxVFo2p9xa7WWEqIfafWlPhDEERrpxFBqSUVS9QWIXa1pDR2sdVSRSyltH37nfe9T2bOnXufmXtnzp0z8zlJ89w5d+ac3/n8zv32zFl7GGMWLf5HgAAEIFB4AksU3kIMhAAEIPB/AggWVQECEPCGAILljaswFAIQQLCoAxCAgDcEECxvXIWhEIAAgkUdgAAEvCGAYHnjKgyFAAQQLOoABCDgDQEEyxtXYSgEIIBgUQcgAAFvCCBY3rgKQyEAAQSLOgABCHhDAMHyxlUYCgEIIFjUAQhAwBsCCJY3rsJQCEAAwaIOQAAC3hBAsLxxFYZCAAIIFnUAAhDwhgCC5Y2rMBQCEECwqAMQgIA3BBAsb1yFoRCAAIJFHYAABLwhgGB54yoMhQAEECzqAAQg4A0BBMsbV2EoBCCAYFEHIAABbwggWN64CkMhAIFSC9YyyyxjRowYgZchAIGSECitYEmsxo0bZwYMGFASV1EMCECglIJVE6uBAwfiYQhAoEQESidYiFWJaidFgYBFoFSChVhZ3uUSAiUjUBrBQqxKVjMpDgRiCJRCsBCrGM8SBYESEvBesBCrEtZKigSBBgS8FizEqoFXiYZASQl4K1iIVUlrJMWCQBMCXgoWYtXEo3wFgRIT8E6wEKsS10aKBoFuCHglWIhVN97kawiUnIA3goVYlbwmUjwIJCDghWAhVgk8yS0QqACBwgsWYlWBWkgRIZCQQKEFC7FK6EVug0BFCBRWsBCritRAigmBFAQKKViIVQoPcisEKkSgcIKFWFWo9lFUCKQkUCjBQqxSeo/bIVAxAoURLMSqYjWP4kKgBQK9Wngm80cQq8yRdiW43HLLmeHDh3dd68P3339vZs6cGYnjojmBzTbbzKy66qqRm1566SXz+++/R+K4yJdAxwULscrXweuuu6555plnIplMmTLFHHLIIZE4LpoTuOiii8x+++0XuWno0KHm/fffj8RxkS+Bjr4SIlb5OpfUIVA2Ah0TLMSqbFWJ8kAgfwIdESzEKn/HkgMEykjAuWAhVmWsRpQJAm4IOBWsTojVzz//XEeyf//+dXFEQAACxSfgTLA6IVbC//bbb9d5Yc6cOXVxREAAAsUn4Eywjj76aDNw4ECnRJ544gnz66+/Os2TzCAAgfwIOJuHZU+6y69I/0tZYqWJfeGw8sorm3POOScclevnL7/80kycODHXPEgcAlUi4EywXEKNEyvlv9JKKzkTrK+++sqMGjXKZbHJCwKlJ+DsldAVSVus+vft6SrrrnxqYvXZZ58FcT179jQrrLBC1/d8gAAEWiNQqhaWLVZ7Du1jRg7ubcZPm9sanRaeihOrI444wjz55JMtpObukX79+pntt9/ebL311mbQoEFG1//884/54YcfzOuvv24ef/zxYA1iOxatvfbaZtNNNzWDBw82a6yxRrA2b9lllzVLLbVUsCbvl19+MR988EGQ38svv2wWLlzYTnbBs6uttprZaaedzLBhw8xaa61ltLayR48e5rfffjOzZ882H330kXnrrbfMjBkzgvK2nWFMAloe1atX9Kf2559/GnUZENIRiFJM92yh7o4Tq0lj+5upM9wtTm0kVuuvv34hBatPnz7m8MMPN0cddVQgVEssEd/gPv74481ff/1lrr/+eqM1df/++29i32vR8Pnnn29Gjx4dvJInffCLL74wV199tbnxxhvNokWLkj7Wdd+WW25pJkyYYHbbbTfTqFxdNy/+oEXMd999tznxxBPD0W1/Puigg8z9998fSUfl2XfffRGsCJVkF/E1NNmzhbmrkVj16tnDmY3NxMqZEQkz0ivqSSedZCQKt9xyi9lmm226/VH37t3bnHfeeebBBx8MWigJ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"/></a>
				This program makes use of the HHVM - is an open-source virtual machine designed for executing programs
				<br/>written in Hack and PHP. HHVM uses a just-in-time (JIT) compilation approach to achieve superior
				performance while maintaining the development flexibility that PHP provides.
				<br/><br/>
				Hack is a programming language for HHVM. Hack reconciles the fast development cycle of a dynamically
				typed language with the discipline provided by static typing, while adding many features commonly found in
				other modern programming languages.
				<br/>
			</td>
		</tr>
	</table>
	<hr/>
	<h1>Configuration</h1>

	<h2><a name="module_core">Core</a></h2>
	<table>
		<tr>
			<td class="e">PHP Version</td>
			<td class="v"><?= phpversion() ?></td>
		</tr>
	</table>
<?php
	if ($ini = ini_get_all()) {

		ksort($ini);
		print_table($ini,
			['Directive', 'Local Value', 'Master Value', 'Access'],
			false
		);

		?><h2>Access level legend</h2><?php

		print_table([
			'Entry can be set in user scripts, ini_set()' => INI_USER,
			'Entry can be set in php.ini, .htaccess, httpd.conf' => INI_PERDIR,
			'Entry can be set in php.ini or httpd.conf' => INI_SYSTEM,
			'<div style="width:865px">Entry can be set anywhere</div>' => INI_ALL
		]);
	}

?><hr/><?php

	if ($extensions = get_loaded_extensions(true)) {
		natcasesort($extensions);
		print_extension_table($extensions);
	}

?>
	<h2>Environment</h2>
	<table>
		<tr class="h">
			<th>Variable</th>
			<th>Value</th>
		</tr>
		<tr>
			<td class="e">USER</td>
			<td class="v"><?= get_current_user() ?></td>
		</tr>
		<tr>
			<td class="e">HOME</td>
			<td class="v"><?= $_SERVER['DOCUMENT_ROOT'] ?></td>
		</tr>
	</table>
	<h2>PHP Variables</h2>
<?php

	$order = array_flip(['_ENV', '_COOKIE', '_SERVER', '_GET', '_POST', '_REQUEST', '_FILES']);

	foreach ($order as $key => $ignore) {
		if (isset($GLOBALS[$key])) {
			echo '<h2 id="', $key, '">$', $key, '</h2>';
			if (empty($GLOBALS[$key])) {
				echo '<hr>';
			} else {
				print_table($GLOBALS[$key]);
			}
		}
	}

	natcasesort($globals);
	$globals = array_flip($globals);
	unset($globals['GLOBALS']);

	foreach ($globals as $key => $ignore) {
		if (!isset($order[$key])) {
			echo '<h2 id="', $key, '">$', $key, '</h2>';
			if (empty($GLOBALS[$key])) {
				echo '<hr>';
			} else {
				print_table($GLOBALS[$key]);
			}
		}
	}

?>
	<h1>HHVM Credits</h1>
	<table>
		<tr class="h">
			<th>Contributing</th>
		</tr>
		<tr>
			<td class="e">
				We'd love to have your help in making HHVM better. If you're interested, please read our guide to
				<a href="https://github.com/facebook/hhvm/blob/master/CONTRIBUTING.md">contributing</a>.
			</td>
		</tr>
	</table>
	<h2>HHVM License</h2>
	<table>
		<tr class="v">
			<td>
				<p>HHVM is licensed under the PHP and Zend licenses except as otherwise noted.
				</p>

				<p>The Hack typechecker (hphp/hack) is licensed under the BSD license (hphp/hack/LICENSE) with an additional
					grant of patent rights (hphp/hack/PATENTS) except as otherwise noted.
				</p>
			</td>
		</tr>
	</table>
</div>
</body>
</html><?php

function print_table(array $array, $headers = false, $formatkeys = false, $formatnumeric = false) {

	if (empty($array) || !is_array($array)) return;

	echo '<table border="0" cellpadding="3">';

	if (!empty($headers)) {
		if (!is_array($headers))
			$headers = array_keys(reset($array));

		echo '<tr class="h">';
		foreach ($headers as $value)
			echo '<th>', $value, '</th>';
		echo '</tr>';
	}

	foreach ($array as $key => $value) {
		echo '<tr>';

		if (!is_numeric($key) || !$formatkeys)
			echo '<td class="e">',
			($formatkeys ? ucwords(str_replace('_', ' ', $key)) : $key),
			'</td>';

		if (is_array($value)) {
			foreach ($value as $column)
				echo '<td class="v">',
				format_special($column, $formatnumeric),
				'</td>';
		} else
			echo '<td class="v">', format_special($value, $formatnumeric), '</td>';

		echo '</tr>';
	}
	echo '</table>';
}

function print_extension_table(array $array) {

	if (empty($array) || !is_array($array)) return;

	$ucwords = 'ucwords';

	foreach ($array as $key => $value) {
		#$ext = new ReflectionExtension($value);
		echo <<<HTML
<h2>$value</h2>
<table border="0" cellpadding="3">
	<tr>
		<td class="e">{$ucwords(str_replace('_', ' ', $value))} support</td>
		<td class="v">enabled</td>
	</tr>
</table>
HTML;
	}
}

function format_special($value, $formatnumeric) {

	switch (true) {
		case is_array($value):
			$value = '<i>array</i>';
			break;
		case is_object($value):
			$value = '<i>object</i>';
			break;
		case ($value === true):
			$value = '<i>true</i>';
			break;
		case ($value === false):
			$value = '<i>false</i>';
			break;
		case ($value === NULL):
			$value = '<i>null</i>';
			break;
		case ($value === 0 || $value === 0.0 || $value === '0'):
			$value = '0';
			break;
		case empty($value):
			$value = '<i>no value</i>';
			break;
		case (is_string($value) && strlen($value) > 50):
			$value = implode('&#8203;', str_split($value, 45));
			break;
		case ($formatnumeric && is_numeric($value)):
			if ($value > 1048576) $value = round($value / 1048576, 1) . 'M';
			elseif (is_float($value)) $value = round($value, 1);
			break;
	}

	return $value;
}

