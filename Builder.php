<?
namespace Slim\Eloquent;

class Builder extends \Illuminate\Database\Eloquent\Builder {

	public function __construct(\Illuminate\Database\Query\Builder $query)
	{
		$this->query = $query;
	}

	/**
	 * Get a paginator for the "select" statement.
	 *
	 * @param  int    $perPage
	 * @param  int  $currentPage
	 * @return Object
	 */
	public function paginateToArray($perPage = null, $currentPage = 1)
	{
		$perPage = $perPage ?: $this->model->getPerPage();

        $total = $this->count(); //total items
        $last_page = (int) ceil($total/$perPage); //last page
        
        //the range of results we're showing (1-5, 6-10 etc.)
        $from = $total ? (($currentPage - 1) * $perPage + 1) : 0;
		$to = min($total, $currentPage * $perPage);
        
        //get the results
        $data = $this->skip($from-1)->take($perPage);
        
        $previous_page = ($currentPage - 1) >= 1 ? $currentPage - 1 : 1;
		$next_page = ($currentPage+1 <= $last_page) ? $currentPage + 1 : $last_page;

        if($previous_page == $currentPage)
            $previous_page = false;
        
        if($next_page == $currentPage)
            $next_page = false;
        
        
		$result = array(
			'total' => $total,					//total number of pages
            'per_page' => $perPage,				//number of results per page
			'current_page' => $currentPage,		//the current page we're on
            'last_page' => $last_page,			//the last page we have to show
			'from' => $from,					//the result start number (e.g. from result 6)
            'to' => $to,						//the result end number (e.g. to result 6)
            'previous_page' => $previous_page,	//the previous page number
            'next_page' => $next_page,			//the next page number
            'data' => $data->get(),				//the results
		);

        return (object) $result;
	}	

	/**
	 * Get a paginator for the "select" statement.
	 *
	 * @param  int    $perPage
	 * @param  int  $currentPage
	 * @return JSONObject
	 */
	public function paginateToJson($perPage = null, $currentPage = 1)
	{
		$result = $this->paginateToArray($perPage, $currentPage);
		return json_encode($result);
	}

}
