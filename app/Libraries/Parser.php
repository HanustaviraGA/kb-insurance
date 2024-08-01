<?php namespace App\Libraries;

use CodeIgniter\View\Exceptions\ViewException;

class Parser extends \CodeIgniter\View\Parser
{
    public function __construct()
    {
        $viewConfig = new \Config\View();
        $viewPath = ROOTPATH;
        $loader = \Config\Services::loader();
        $debug = CI_DEBUG;
        $logger = \Config\Services::logger();
        parent::__construct($viewConfig, $viewPath, $loader , $debug, $logger);
    }
    
    /**
     * Parse a template
     *
     * Parses pseudo-variables contained in the specified template,
     * replacing them with the data in the second param
     *
     * @param array $options Future options
     */
    protected function parse(string $template, array $data = [], ?array $options = null): string
    {
        if ($template === '') {
            return '';
        }

        $template = $this->includeFile($template);
        // Remove any possible PHP tags since we don't support it
        // and parseConditionals needs it clean anyway...
        $template = str_replace(['<?', '?>'], ['&lt;?', '?&gt;'], $template);
        $template = str_replace(['(', ')'], ['#40', '#41'], $template);

        // temporarily replace the plugin tag so it doesn't trigger an error during the loop
        $template = str_replace(['{+', '+}'], ['#$', '$#'], $template);

        $template = $this->parseComments($template);
        $template = $this->extractNoparse($template);

        // Replace any conditional code here so we don't have to parse as much
        $template = $this->parseConditionals($template);

        
        // loop over the data variables, replacing
        // the content as we go.
        foreach ($data as $key => $val) {
            $escape = true;

            if (is_array($val)) {
                $escape  = false;
                $replace = $this->parsePair($key, $val, $template);
            } else {
                $replace = $this->parseSingle($key, (string) $val);
            }

            foreach ($replace as $pattern => $content) {
                $template = $this->replaceSingle($pattern, $content, $template, $escape);
            }
        }

        // return plugin tag before running parsePlugins
        $template = str_replace(['#$', '$#'], ['{+', '+}'], $template);
        // Handle any plugins before normal data, so that
        // it can potentially modify any template between its tags.
        $template = $this->parsePlugins($template);
        $template = $this->insertNoparse($template);
        $template = str_replace(['#40', '#41'], ['(', ')'], $template);
        
        return $template;
    }

    private function includeFile(string $template){
        preg_match_all("/\@include\s+[\"|\'](.+?)[\"|\']/", $template, $matchs);
        if(!$matchs) return $template;
        $tags = [];
        $replacements = [];
        foreach($matchs[1] as $index => $match){
            $includeFile = $this->viewPath . str_replace('.', '/', $match) . '.php';
            if(!file_exists($includeFile)){
                throw ViewException::forInvalidFile($includeFile);
            }    
            $replacements[] = file_get_contents($includeFile);
            $tags[] = $matchs[0][$index];
        }

        return str_replace($tags, $replacements, $template);
    }

    static public function view($view, $data = [], $isReturn = false, $withUrl = true)
    {
        // $parser  = new Parser();

        if ($withUrl) {
            $data['url'] = base_url();
        }

        $data['rand'] = rand();
        $parserView = (new \App\Libraries\Parser())->setData($data)->renderString(view($view, $data));
        // $parserView = $parser->setData($data)->renderString(view($view, $data));
        if ($isReturn) {
            return $parserView;
        }else{
            echo $parserView;
        }
    }
}