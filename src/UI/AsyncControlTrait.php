<?php

namespace Pd\AsyncControl\UI;

use Nette\Application\UI\Control;
use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\Template;


/**
 * @method render
 */
trait AsyncControlTrait
{

    /**
     * @var callable
     */
    protected $asyncRenderer;

    /** @var bool */
    protected $useTranslate = true;

    public function handleAsyncLoad()
    {
        if (!$this instanceof Control || !($presenter = $this->getPresenter(FALSE)) || !$presenter->isAjax()) {
            return;
        }
        ob_start(function () {
        });
        try {
            $this->renderAsync();
        } catch (\Throwable $e) {
            ob_end_clean();
            throw $e;
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }
        $content = ob_get_clean();
        $presenter->getPayload()->snippets[$this->getSnippetId('async')] = $content;
        $presenter->sendPayload();
    }


    /**
     * @param string|NULL $linkMessage
     * @param array|NULL $linkAttributes
     */
    public function renderAsync($linkMessage = NULL, array $linkAttributes = NULL)
    {
        if (
            $this instanceof Control
            && $this->getPresenter()->getParameter('_escaped_fragment_') === NULL
            && strpos((string)$this->getPresenter()->getParameter(Presenter::SIGNAL_KEY), sprintf('%s-', $this->getUniqueId())) !== 0
        ) {
            $template = $this->createTemplate();
            if ($template instanceof Template) {
                $template->add('link', new AsyncControlLink($linkMessage, $linkAttributes));
            }
            $template->useTranslate = $this->useTranslate;
            $template->setFile(__DIR__ . '/templates/asyncLoadLink.latte');
            $template->render();
        } elseif (is_callable($this->asyncRenderer)) {
            call_user_func($this->asyncRenderer);
        } else {
            $this->render();
        }
    }


    /**
     * @param callable $renderer
     */
    public function setAsyncRenderer(callable $renderer)
    {
        $this->asyncRenderer = $renderer;
    }
}
