<?php

namespace PHLAK\SemVer\Tests;

use PHLAK\SemVer;
use PHLAK\SemVer\Exceptions\InvalidVersionException;
use PHPUnit\Framework\TestCase;

class VersionTest extends TestCase
{
    public function test_it_can_be_initialized()
    {
        $version = new SemVer\Version('v1.3.37');

        $this->assertInstanceOf(SemVer\Version::class, $version);
    }

    public function test_it_can_be_initialized_with_the_helper_function()
    {
        $version = semver('v1.3.37');

        $this->assertInstanceOf(SemVer\Version::class, $version);
    }

    public function test_it_throws_a_runtime_exception_for_an_invalid_version()
    {
        $this->expectException(InvalidVersionException::class);

        new SemVer\Version('not.a.version');
    }

    public function test_it_can_set_and_retrieve_a_version()
    {
        $version = new SemVer\Version('v1.3.37');
        $version->setVersion('v2.4.48');

        $this->assertEquals('2.4.48', (string) $version);
    }

    public function test_it_can_return_a_prefixed_version_string()
    {
        $version = new SemVer\Version('v1.3.37');

        $this->assertEquals('v1.3.37', $version->prefix());
        $this->assertEquals('x1.3.37', $version->prefix('x'));
    }

    public function test_it_can_be_cast_to_a_string()
    {
        $version = new SemVer\Version('v1.3.37');

        $this->assertEquals('1.3.37', (string) $version);
    }

    public function test_it_can_get_individual_properties()
    {
        $version = new SemVer\Version('v1.3.37-alpha.5+007');

        $this->assertEquals(1, $version->major);
        $this->assertEquals(3, $version->minor);
        $this->assertEquals(37, $version->patch);
        $this->assertEquals('alpha.5', $version->preRelease);
        $this->assertEquals('007', $version->build);
    }

    public function test_it_can_increment_major()
    {
        $version = new SemVer\Version('v1.3.37');
        $version->incrementMajor();

        $this->assertEquals('2.0.0', (string) $version);
    }

    public function test_it_can_set_major()
    {
        $version = new SemVer\Version('v1.3.37');
        $version->setMajor(7);

        $this->assertEquals('7.0.0', (string) $version);
    }

    public function test_it_can_increment_minor()
    {
        $version = new SemVer\Version('v1.3.37');
        $version->incrementMinor();

        $this->assertEquals('1.4.0', (string) $version);
    }

    public function test_it_can_set_minor()
    {
        $version = new SemVer\Version('v1.3.37');
        $version->setMinor(5);

        $this->assertEquals('1.5.0', (string) $version);
    }

    public function test_it_can_increment_patch()
    {
        $version = new SemVer\Version('v1.3.37');
        $version->incrementPatch();

        $this->assertEquals('1.3.38', (string) $version);
    }

    public function test_it_can_set_patch()
    {
        $version = new SemVer\Version('v1.3.37');
        $version->setPatch(12);

        $this->assertEquals('1.3.12', (string) $version);
    }

    public function test_it_can_set_pre_release()
    {
        $version = new SemVer\Version('v1.3.37');
        $version->setPreRelease('alpha.5');

        $this->assertEquals('1.3.37-alpha.5', (string) $version);
    }

    public function test_it_can_unset_pre_release()
    {
        $version = (new SemVer\Version('v1.3.37-alpha.5'))->setPreRelease(null);

        $this->assertNull($version->preRelease);
    }

    public function test_it_can_set_build()
    {
        $version = new SemVer\Version('v1.3.37');
        $version->setBuild('007');

        $this->assertEquals('1.3.37+007', (string) $version);
    }

    public function test_it_can_unset_build()
    {
        $version = (new SemVer\Version('v1.3.37+007'))->setBuild(null);

        $this->assertNull($version->build);
    }

    public function test_it_can_be_greater_than_another_semver_object()
    {
        $version = new SemVer\Version('v1.3.37');

        $this->assertTrue($version->gt(new SemVer\Version('v0.5.0')));
        $this->assertTrue($version->gt(new SemVer\Version('v1.3.36')));
        $this->assertFalse($version->gt(new SemVer\Version('v1.3.38')));
        $this->assertFalse($version->gt(new SemVer\Version('v1.3.37')));
    }

    public function test_it_can_be_less_than_another_semver_object()
    {
        $version = new SemVer\Version('v1.3.37');

        $this->assertTrue($version->lt(new SemVer\Version('v1.3.38')));
        $this->assertTrue($version->lt(new SemVer\Version('v1.4.0')));
        $this->assertFalse($version->lt(new SemVer\Version('v1.3.36')));
        $this->assertFalse($version->lt(new SemVer\Version('v1.3.37')));
    }

    public function test_it_can_be_equal_to_another_semver_object()
    {
        $version = new SemVer\Version('v1.3.37');

        $this->assertTrue($version->eq(new SemVer\Version('v1.3.37')));
        $this->assertFalse($version->eq(new SemVer\Version('v1.2.3')));
    }

    public function test_it_can_be_not_equal_to_another_semver_object()
    {
        $version = new SemVer\Version('v1.3.37');

        $this->assertTrue($version->neq(new SemVer\Version('v1.2.3')));
        $this->assertFalse($version->neq(new SemVer\Version('v1.3.37')));
    }

    public function test_it_can_be_greater_than_or_equal_to_another_semver_object()
    {
        $version = new SemVer\Version('v1.3.37');

        $this->assertTrue($version->gte(new SemVer\Version('v1.2.3')));
        $this->assertTrue($version->gte(new SemVer\Version('v1.3.37')));
        $this->assertFalse($version->gte(new SemVer\Version('v2.3.4')));
    }

    public function test_it_can_be_less_than_or_equal_to_another_semver_object()
    {
        $version = new SemVer\Version('v1.3.37');

        $this->assertTrue($version->lte(new SemVer\Version('v2.3.4')));
        $this->assertTrue($version->lte(new SemVer\Version('v1.3.37')));
        $this->assertFalse($version->lte(new SemVer\Version('v1.2.3')));
    }

    public function test_setting_the_major_version_resets_appropriate_properties()
    {
        $version = new SemVer\Version('v1.3.37-alpha.5+007');
        $version->setMajor(2);

        $this->assertEquals(2, $version->major);
        $this->assertEquals(0, $version->minor);
        $this->assertEquals(0, $version->patch);
        $this->assertNull($version->preRelease);
        $this->assertNull($version->build);
    }

    public function test_setting_the_minor_version_resets_appropriate_properties()
    {
        $version = new SemVer\Version('v1.3.37-alpha.5+007');
        $version->setMinor(4);

        $this->assertEquals(1, $version->major);
        $this->assertEquals(4, $version->minor);
        $this->assertEquals(0, $version->patch);
        $this->assertNull($version->preRelease);
        $this->assertNull($version->build);
    }

    public function test_setting_the_patch_version_resets_appropriate_properties()
    {
        $version = new SemVer\Version('v1.3.37-alpha.5+007');
        $version->setPatch(38);

        $this->assertEquals(1, $version->major);
        $this->assertEquals(3, $version->minor);
        $this->assertEquals(38, $version->patch);
        $this->assertNull($version->preRelease);
        $this->assertNull($version->build);
    }

    public function test_it_compares_pre_release_tags()
    {
        $alpha = new SemVer\Version('v1.3.37-alpha');
        $beta = new SemVer\Version('v1.3.37-beta');

        $this->assertTrue($alpha->lt($beta));
        $this->assertFalse($alpha->gt($beta));
        $this->assertTrue($alpha->lte($beta));
        $this->assertFalse($alpha->gte($beta));
        $this->assertFalse($alpha->eq($beta));
    }

    public function test_it_ignores_the_build_version_when_comparing_versions()
    {
        $oldBuild = new SemVer\Version('v1.3.37-alpha.5+006');
        $newBuild = new SemVer\Version('v1.3.37-alpha.5+007');

        $this->assertTrue($oldBuild->eq($newBuild));
        $this->assertFalse($oldBuild->neq($newBuild));
        $this->assertFalse($oldBuild->gt($newBuild));
        $this->assertFalse($oldBuild->lt($newBuild));
        $this->assertTrue($oldBuild->gte($newBuild));
        $this->assertTrue($oldBuild->lte($newBuild));
    }
}
