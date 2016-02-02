			<{include file="App/Public/header.tpl"}>
			<{include file="App/Public/left_bar.tpl"}>
			
			<!-- 中部 右部 begin -->
			<div id="main">

				<{include file="App/Public/tab.tpl"}>

				<div class="box">
					<div class="c_t">
						<div class="c_tl"></div>
						<div class="c_tr"></div>
						<div class="clearit"></div>
					</div>
					<div class="info" style="display:block;">
						<!-- first table begin -->
					<div id="rank">
						<div id="firstIn">
							<div class="tt ">
								<span class="fl">输入Query</span>
								<div class="fr">
								</div>
								<div class="clearit"></div>
							</div>
							<!-- second table begin -->
							<form action="<{$arrLinkList.search_query_list}>" method="post">
								<table class="sortable datagrid stripe_tb sortable_ed" id="sortable" cellpadding="0" cellspacing="0" width="100%">
									<thead>
										<tr class="alt">
											<th align="center">Location:</th>
											<td align="center">
												<select name="location">
													<{ foreach from=$arrLocation item=location }>
														<option value='<{$location.value}>'><{$location.name}></option>
													<{/foreach}>
												</select>	
											</td>
										</tr>

										<tr class="alt">
											<th align="center">Query:</th>
											<td align="center">
												<textarea id="table_textarea" name="search_query" placeholder="input query">
													<{$arrData.query}>
												</textarea>
											</td>
										</tr>
										<tr class="alt">
											<th align="center"></th>
											<td align="center">
												<span style="color:red;"><{$msg}></span>
											</td>
										</tr>

									</thead>
									<tfoot>
									</tfoot>
								</table>
								<div class="tip2" id="globalTip">
									<table>
									<tr>
										<td align="center">
											<input class="buttonLook2 bornChart" id="inquire" value="提交" type="submit"/>	
										</td>
									</tr>
									</table>
								</div>
							</form>
							<!-- second tabls end -->
						</div>
					</div>

					<div id="rankJump" style="height: 360px; display: block;">
					</div>
				</div>

				<a href="#rankJump" name="rankJump" id="rankJump"></a>
				<div class="clearit"></div>
				<div class="c_b">
					<div class="c_bl"></div>
					<div class="c_br"></div>
					<div class="clearit"></div>
				</div>
			</div>
<script>
	var query_text = document.getElementById( "table_textarea" );
	autoTextarea( query_text );
</script>

<{ include file="App/Public/footer.tpl" }>
