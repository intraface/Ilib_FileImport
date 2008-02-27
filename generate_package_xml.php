<?php
/**
 * package.xml generation script
 *
 * @category Ilib
 * @package  Ilib_FileImport
 * @author   Sune Jensen <sj@sunet.dk>
 * @license  BSD license
 * @version  @package-version@
 */

require_once 'PEAR/PackageFileManager2.php';
PEAR::setErrorHandling(PEAR_ERROR_DIE);

$version = '0.0.1';
$notes = '* initial package';
$stability = 'alpha';

$pfm = new PEAR_PackageFileManager2();
$pfm->setOptions(
    array(
        'baseinstalldir'    => '/',
        'filelistgenerator' => 'file',
        'packagedirectory'  => dirname(__FILE__) . '/src/',
        'packagefile'       => 'package.xml',
        'ignore'            => array(
            '*.tgz'
            ),
        'exceptions'        => array(),
        'simpleoutput'      => true,
    )
);

$pfm->setPackage('Ilib_FileImport');
$pfm->setSummary('Easy file import');
$pfm->setDescription('This package makes it easy to import data from files. Returns an array with the dataset. Currently supports CSV');
$pfm->setChannel('public.intraface.dk');
$pfm->setLicense('BSD license', 'http://www.opensource.org/licenses/bsd-license.php');

$pfm->addMaintainer('lead', 'lsolesen', 'Lars Olesen', 'lars@legestue.net');
$pfm->addMaintainer('lead', 'sune.t.jensen', 'Sune Jensen', 'sj@sunet.dk');

$pfm->setPackageType('php');

$pfm->setAPIVersion($version);
$pfm->setReleaseVersion($version);
$pfm->setAPIStability($stability);
$pfm->setReleaseStability($stability);
$pfm->setNotes($notes);
$pfm->addRelease();

$pfm->addGlobalReplacement('package-info', '@package-version@', 'version');

$pfm->clearDeps();
$pfm->setPhpDep('5.1.0');
$pfm->setPearinstallerDep('1.5.0');

$pfm->generateContents();

if (isset($_GET['make']) || (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make')) {
    if ($pfm->writePackageFile()) {
        exit('package written');
    }
} else {
    $pfm->debugPackageFile();
}
?>