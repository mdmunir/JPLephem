<?php

namespace Marando\JPLephem\DE;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-11-04 at 00:39:45.
 */
class ReaderTest extends \PHPUnit_Framework_TestCase {

  ///////
  //////////////
  ///////
  /////////////////////
  ///////

  public function testLTT() {
    $de = new Reader();

    $pvA = $de->jde(2457101.5)->position(SSObj::Jupiter(), SSObj::Earth());
    $pvB = $de->jde(2457101.5)->observe(SSObj::Jupiter(), SSObj::Earth(), $lt);

    var_dump($pvA, $pvB, $lt->days);
  }

  /**
   * @covers Marando\JPLephem\DE\Reader::jde
   * @todo   Implement testJde().
   */
  public function testJde() {
    // Remove the following lines when you implement this test.
    $this->markTestIncomplete(
            'This test has not been implemented yet.'
    );
  }

  /**
   * @covers Marando\JPLephem\DE\Reader::position
   * @todo   Implement testPosition().
   */
  public function testPosition() {
    // Remove the following lines when you implement this test.
    $this->markTestIncomplete(
            'This test has not been implemented yet.'
    );
  }

  /**
   * @covers Marando\JPLephem\DE\Reader::observe
   * @todo   Implement testObserve().
   */
  public function testObserve() {
    // Remove the following lines when you implement this test.
    $this->markTestIncomplete(
            'This test has not been implemented yet.'
    );
  }

  /**
   * @covers Marando\JPLephem\DE\Reader::interp
   * @todo   Implement testInterp().
   */
  public function testInterp() {
    // Remove the following lines when you implement this test.
    $this->markTestIncomplete(
            'This test has not been implemented yet.'
    );
  }

  /**
   * @covers Marando\JPLephem\DE\Reader::__get
   * @todo   Implement test__get().
   */
  public function test__get() {
    // Remove the following lines when you implement this test.
    $this->markTestIncomplete(
            'This test has not been implemented yet.'
    );
  }

  //----------------------------------------------------------------------------
  // Functional Tests
  //----------------------------------------------------------------------------

  public function testall() {
    $jde = 2451545.5;

    $de = new Reader();
    $de->jde($jde);

    $ssobjs = [
        SSObj::SolarBary(),
        SSObj::Sun(),
        SSObj::Mercury(),
        SSObj::Venus(),
        SSObj::Earth(),
        SSObj::EarthBary(),
        SSObj::Moon(),
        SSObj::Mars(),
        SSObj::Jupiter(),
        SSObj::Saturn(),
        SSObj::Uranus(),
        SSObj::Neptune(),
        SSObj::Pluto()
    ];

    foreach ($ssobjs as $t) {
      foreach ($ssobjs as $c) {
        $pv = $de->position($t, $c);

        $format = '%+0.15E';

        continue;
        echo "\n\n$jde\n$c -> $t";
        echo "\n[ " . sprintf($format, $pv[0]);
        echo " " . sprintf($format, $pv[1]);
        echo " " . sprintf($format, $pv[2]) . " ]";
        echo "\n[ " . sprintf($format, $pv[3]);
        echo " " . sprintf($format, $pv[4]);
        echo " " . sprintf($format, $pv[5]) . " ]";
      }
    }

    echo "\n";
  }

  public function testpo() {
    // Define number of tests to run
    $testLimit = 250;

    // Create reader, and obtain testpo file reference
    $reader = new Reader(DE::DE421());
    $testpo = Reader::testpo(DE::DE421());

    // Seek initial test line
    $testpo->seek(8);

    // Iterate through each line in the testpo file
    for ($i = 0; $i < $testLimit; $i++) {
      // Read and split next line to array
      $testpo->next();
      $line = $testpo->splitCurrent(' ');

      // Check if array has the tests
      if (count($line) != 7)
        continue;

      // Parse out test values
      $jde    = $line[2];
      $target = $line[3];
      $center = $line[4];
      $elem   = $line[5];
      $valExp = (float)$line[6];

      // Only test Planets
      if ($target > 9 || $center > 9)
        continue;

      // Get SSObj instance for target & center
      $target = $target == 3 ? SSObj::Earth() : new SSObj($target);
      $center = $center == 3 ? SSObj::Earth() : new SSObj($center);

      // Interpolate position/velocity & grab test coordinate element
      $posvel = $reader->jde($jde)->position($target, $center);
      $valAct = $posvel[$elem - 1];

      /*
        $e = sprintf('%+11.13E', $valExp);
        $a = sprintf('%+11.13E', $valAct);
        echo "\n{$center} -> {$target}\n--- $e\n+++ $a\n";
       *
       */

      $this->assertEquals($valExp, $valAct, $jde, 1e-13);
    }

    echo "\n";
  }

  public function testMultiDate() {
    $target = SSObj::Mercury();
    $center = SSObj::Earth();
    $jd1    = 2451545;
    $jdN    = 2451546;
    $step   = 0.1;
    $format = '%+0.15E';

    //echo "\n\n$center -> $target\n";

    $de = new Reader();
    for ($jd = $jd1; $jd < $jdN; $jd += $step) {
      $pv = $de->jde($jd)->position($target, $center);

      /*
        echo "\n$jd\n[ " . sprintf($format, $pv[0]);
        echo " " . sprintf($format, $pv[1]);
        echo " " . sprintf($format, $pv[2]) . " ]";
        echo "\n[ " . sprintf($format, $pv[3]);
        echo " " . sprintf($format, $pv[4]);
        echo " " . sprintf($format, $pv[5]) . " ]\n";
       *
       */
    }
  }

}
