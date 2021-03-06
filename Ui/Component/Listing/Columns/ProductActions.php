<?php

namespace GhoSter\AutoInstagramPost\Ui\Component\Listing\Columns;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use GhoSter\AutoInstagramPost\Model\Config as InstagramConfig;

/**
 * Class ProductActions
 *
 * @api
 * @since 100.0.2
 */
class ProductActions extends \Magento\Catalog\Ui\Component\Listing\Columns\ProductActions
{
    const URL_PATH_POST = 'auto_instagram/manage/post';

    /**
     * @var InstagramConfig
     */
    protected $config;

    /**
     * ProductActions constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param InstagramConfig $config
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        InstagramConfig $config,
        array $components = [],
        array $data = []
    )
    {
        $this->config = $config;
        parent::__construct(
            $context,
            $uiComponentFactory,
            $urlBuilder,
            $components,
            $data
        );
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $storeId = $this->context->getFilterParam('store_id');

            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['is_posted_to_instagram']) && $this->config->isEnabled()) {
                    $item[$this->getData('name')]['post_instagram_action'] = [
                        'href' => $this->urlBuilder->getUrl(
                            static::URL_PATH_POST,
                            ['id' => $item['entity_id'], 'store' => $storeId]
                        ),
                        'label' => $item['is_posted_to_instagram'] === true ? __('RePost') : __('Post'),
                        'confirm' => [
                            'title' => __('Post "${ $.$data.name }" to Instagram'),
                            'message' => $item['is_posted_to_instagram'] === true ? __('Are you sure you wan\'t to re-post this product to Instagram?') : __('Are you sure you wan\'t to post this product to Instagram?')
                        ],
                        'hidden' => false,
                    ];
                }
            }
        }

        return $dataSource;
    }
}
