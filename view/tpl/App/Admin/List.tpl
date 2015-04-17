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
								<span class="fl">APP列表</span>
								<div class="fr">
								</div>
								<div class="clearit"></div>
							</div>
							<!-- second table begin -->
							<table class="sortable datagrid stripe_tb sortable_ed" id="tablesort_sina_serverIp" cellpadding="0" cellspacing="0" width="100%">
								<thead>
									<tr class="alt">
										<th align="center">Id</th>
										<th align="center">管理员</th>
										<th align="center">类型</th>
										<th align="center">状态</th>
										<th align="center">时间</th>
										<th align="center">编辑</th>
									</tr>
								</thead>
								<tbody>
								<{ foreach from=$arrAdminList item=app }>
									<tr class="">
										<td align="left"><{$app.id}></td>
										<td align="left"><{$app.name}></td>
										<td align="left"><{$app.level_str}></td>
										<td align="left"<{if $app.status eq 1}>class='status_red'<{/if}>><{$app.status_str}></td>
										<td align="left"><{$app.c_time|date_format:"%Y-%m-%e %H:%M:%S"}></td>
										<td align="left"><a href="<{$arrLink.api_admin_edit}>&id=<{$app.id}>">编辑</a></td>
									</tr>
								<{/foreach}>
								</tbody>
								<tfoot>
								</tfoot>
							</table>
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

<{ include file="App/Public/footer.tpl" }>
