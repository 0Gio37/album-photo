<?php

namespace App\Tests;

use App\Entity\Photo;
use App\Repository\PhotoRepository;
use App\Service\Services;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SliderDetailsPhotoTest extends KernelTestCase
{
    public function testReturnPrevPhoto(): void
    {
        self::bootKernel();
        $photoRepository = self::$container->get(PhotoRepository::class);
        $testedStatus = "prev-photo";
        $testedDate = new \DateTime("2022-10-15 15:34:42");
        $testedIdAlbum = 4;

        $result = (new Services())
            ->SliderDetailsPhoto($photoRepository, $testedStatus, $testedIdAlbum,$testedDate);

        $this->assertTrue(is_object($result));
        $this->assertEquals($testedIdAlbum,
            $result->getAlbum()->getId(),
            "L'ALBUM N'EST PAS LE BON");
        $this->assertSame("2022-10-14 15:39:48",
            $result->getCreateAt()->format("Y-m-d H:i:s"),
            "L'ANNEE N'EST PAS CELLE JUSTE AVANT");
    }

    public function testReturnNextPhoto(): void
    {
        self::bootKernel();
        $photoRepository = self::$container->get(PhotoRepository::class);
        $testedStatus = "next-photo";
        $testedIdAlbum = 4;
        $testedDate = new \DateTime("2022-10-20 16:50:00");
        $result = (new Services())->SliderDetailsPhoto($photoRepository, $testedStatus, $testedIdAlbum,$testedDate);

        $this->assertTrue(is_object($result));
        $this->assertEquals($testedIdAlbum, $result->getAlbum()->getId(), "L'ALBUM N'EST PAS LE BON");
        $this->assertSame("2022-10-21 16:54:45", $result->getCreateAt()->format("Y-m-d H:i:s"), "L'ANNEE N'EST PAS CELLE JUSTE APRES");
    }
}
