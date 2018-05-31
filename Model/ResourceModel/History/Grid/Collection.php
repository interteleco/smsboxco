<?php
namespace Interteleco\Smsbox\Model\ResourceModel\History\Grid;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Psr\Log\LoggerInterface as Logger;

class Collection extends SearchResult
{

    /**
     * Collection constructor.
     *
     * @param    EntityFactory $entityFactory
     * @param    Logger        $logger
     * @param    FetchStrategyInterface $fetchStrategy
     * @param    EventManager  $eventManager
     * @internal param string $mainTable
     * @internal param string $resourceModel
     */
    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategyInterface $fetchStrategy,
        EventManager $eventManager
    ) {

        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            'interteleco_smsbox',
            'Interteleco\Smsbox\Model\ResourceModel\History'
        );
    }
}
