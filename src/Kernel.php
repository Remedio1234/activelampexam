class Kernel extends BaseKernel
{
    // ...

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/'.$this->environment.'/cache';
    }
}