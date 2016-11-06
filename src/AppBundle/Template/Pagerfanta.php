<?php

namespace AppBundle\Template;

use Pagerfanta\View\Template\DefaultTemplate;

class Pagerfanta extends DefaultTemplate
{

	static protected $defaultOptions = array(
        'previous_message'   => 'Previous',
        'next_message'       => 'Next',
        'css_disabled_class' => 'disabled',
        'css_dots_class'     => 'dots',
        'css_current_class'  => 'current',
        'css_previous_class' => 'pagination-previous',
        'css_next_class'     => 'pagination-next',
        'dots_text'          => '...',
        'container_template' => '<ul class="pagination text-center" role="navigation" aria-label="Pagination">%pages%</ul>',
        'page_template'      => ' <li class="%class%"><a href="%href%">%text%</a></li>',
        'span_template'      => ' <li class="%class%">%text%</li>',
    );

    public function previousDisabled()
    {
        return $this->generateSpan($this->option('css_disabled_class').' '.$this->option('css_previous_class'), $this->option('previous_message'));
    }

     public function nextDisabled()
    {
        return $this->generateSpan($this->option('css_disabled_class').' '.$this->option('css_next_class'), $this->option('next_message'));
    }

	public function nextEnabled($page)
    {
        return $this->pageWithText($page, $this->option('next_message'), $this->option('css_next_class'));
    }
    
    public function previousEnabled($page)
    {
        return $this->pageWithText($page, $this->option('previous_message'), $this->option('css_previous_class'));
    }

    private function generateSpan($class, $page)
    {
        $search = array('%class%', '%text%');
        $replace = array($class, $page);
        return str_replace($search, $replace, $this->option('span_template'));
    }

    public function pageWithText($page, $text, $class='')
    {
        $search = array('%href%', '%text%', '%class%');
        $href = $this->generateRoute($page);
        $replace = array($href, $text, $class);
        return str_replace($search, $replace, $this->option('page_template'));
    }

}