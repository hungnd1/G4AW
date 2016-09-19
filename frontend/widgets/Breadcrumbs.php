<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 19/03/2016
 * Time: 3:03 PM
 */
namespace frontend\widgets;


use common\helpers\StringUtils;
use yii\helpers\Html;

class Breadcrumbs extends \yii\widgets\Breadcrumbs
{
    /**
     * @var string the name of the breadcrumb container tag.
     */
    public $tag = 'div';
    /**
     * @var array the HTML attributes for the breadcrumb container tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = ['class' => 'brc-page'];
    public $itemTemplate = "{link} <span>/</span>\n";
    public $activeItemTemplate = "{link}\n";
    /**
     * Renders the widget.
     */
    public function run()
    {
        if (empty($this->links)) {
            return;
        }
        $links = [];
        if ($this->homeLink === null) {
            $links[] = $this->renderItem([
                'label' => \Yii::t('yii', 'Home'),
                'url' => \Yii::$app->homeUrl,
            ], $this->itemTemplate);
        } elseif ($this->homeLink !== false) {
            $links[] = $this->renderItem($this->homeLink, $this->itemTemplate);
        }
        foreach ($this->links as $key=> $link) {
            if (!is_array($link)) {
                $link = ['label' => $link];
            }
            \Yii::warning($key== (count($this->links) -1));
            $links[] = $this->renderItem($link, $key != (count($this->links) -1) ? $this->itemTemplate : $this->activeItemTemplate);
        }
        echo Html::tag($this->tag, implode('', $links), $this->options);
    }

}