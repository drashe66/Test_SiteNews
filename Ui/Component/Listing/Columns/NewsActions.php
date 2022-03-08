<?php

namespace Test\SiteNews\Ui\Component\Listing\Columns;

use Magento\Ui\Component\Listing\Columns\Column;

class NewsActions extends Column
{
    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        $dataSource = parent::prepareDataSource($dataSource);

        if (empty($dataSource['data']['items'])) {
            return $dataSource;
        }

        foreach ($dataSource['data']['items'] as &$item) {
            $item[$this->getData('name')] = [
                'edit' => [
                    'href' => $this->context->getUrl(
                    'sitenews/news/edit',
                    ['id' => $item['id']]
                    ),
                    'label' => __('Edit')
                ],
                'delete' => [
                    'href' => $this->context->getUrl(
                        'sitenews/news/delete',
                        ['id' => $item['id']]
                    ),
                    'label' => __('Delete'),
                    'confirm' => [
                        'title' => __('Delete News Item'),
                        'message' => __('Are you sure you want to delete a record?')
                    ]
                ]
            ];
        }

        return $dataSource;
    }
}
