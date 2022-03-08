<?php

namespace Test\SiteNews\Block\Adminhtml\News\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getId()) {
            $data = [
                'label' => __('Delete News Item'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\''
                    . __('Are you sure you want to delete?')
                    . '\', \'' . $this->getUrl('*/*/delete', ['id' => $this->getId()]) . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }
}
