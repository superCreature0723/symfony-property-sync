// src/Service/PropertyService.php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Property;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;

class PropertyService
{
    private const CACHE_EXPIRATION = 3600; // 1 Hour
    private array $apis = [
        'sprengnetter' => 'https://api.avm.sprengnetter.de/service/api/help/index.html',
        'europace' => 'https://github.com/europace/baufi-passende-vorschlaege-api/tree/main#request-financial-proposals'
    ];

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly EntityManagerInterface $entityManager,
        private readonly CacheItemPoolInterface $cache,
        private readonly LoggerInterface $logger
    ) {}

    public function fetchProperties(): void
    {
        foreach ($this->apis as $source => $url) {
            $cacheKey = "properties_$source";
            $cached = $this->cache->getItem($cacheKey);

            if (!$cached->isHit()) {
                try {
                    $response = $this->client->request('GET', $url);
                    $data = $response->toArray();

                    $this->storeProperties($data, $source);

                    $cached->set($data);
                    $cached->expiresAfter(self::CACHE_EXPIRATION);
                    $this->cache->save($cached);
                } catch (\Exception $e) {
                    $this->logger->error("API $source failed: " . $e->getMessage());
                }
            }
        }
    }

    private function storeProperties(array $data, string $source): void
    {
        foreach ($data as $item) {
            $property = new Property();
            $property->setAddress($item['address'] ?? 'Unknown');
            $property->setPrice($item['price'] ?? 0.0);
            $property->setSource($source);
            $property->setLastUpdated(new \DateTime());

            $this->entityManager->persist($property);
        }
        $this->entityManager->flush();
    }
}
