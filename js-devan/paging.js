function paging($selector){

	if(typeof $selector == 'undefined')
	{
		$selector = $("#data_preview tbody tr");
	}

	window.tp = new Pagination('#tablePaging', {
		itemsCount:$selector.length,
		pageSize : 10,
		onPageSizeChange: function (ps) {
			console.log('changed to ' + ps);
		},
		onPageChange: function (paging) {
			//custom paging logic here
			//console.log(paging);
			var start = paging.pageSize * (paging.currentPage - 1),
				end = start + paging.pageSize,
				$rows = $selector;

			$rows.hide();

			for (var i = start; i < end; i++) {
				$rows.eq(i).show();
			}
		}
	});
}