<?php

namespace Tests\Unit\Example;

use App\Data\Shared\ResponseData;
use App\Http\Controllers\Example\ItemController;
use App\Services\Example\ItemService;
use App\Http\Requests\GenericRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tests\TestCase;
use Mockery;

/**
 * Class ItemUnitTest
 *
 * @package Tests
 */
class ItemUnitTest extends TestCase
{
    private string $endpoint = '/items';
    
    /**
     * @var ItemController
     */
    private ItemController $itemController;

    /**
     * @var ItemService|Mockery\MockInterface
     */
    private ItemService|Mockery\MockInterface $itemService;

    /**
     * Set up the test case.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->itemService = Mockery::mock(ItemService::class);
        $this->itemController = new ItemController($this->itemService);
    }

    /**
     * Tear down the test case.
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test the index method of the ItemController.
     */
    public function testIndexMethod(): void
    {
        $request = GenericRequest::createFrom(Request::create($this->endpoint, 'GET'));
        $responseData = ResponseData::success('Test success response');
        $this->itemService
            ->shouldReceive('get')
            ->once()
            ->with(Mockery::on(function ($argument) use ($request) {
                return json_encode($argument) === json_encode($request->toData());
            }))
            ->andReturn($responseData);

        $response = $this->itemController->index($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->status());
    }
}
