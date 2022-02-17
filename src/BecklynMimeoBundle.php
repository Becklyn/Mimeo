<?php declare(strict_types=1);

namespace Becklyn\Mimeo;

use Becklyn\Mimeo\DependencyInjection\MimeoBundleExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BecklynMimeoBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function getContainerExtension () : ?ExtensionInterface
    {
        return new MimeoBundleExtension();
    }
}
