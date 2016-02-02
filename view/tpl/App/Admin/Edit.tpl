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
								<span class="fl">管理员编辑</span>
								<div class="fr">
								</div>
								<div class="clearit"></div>
							</div>
							<!-- second table begin -->
							<form action="<{$arrLink.api_admin_save}>" method="post">
							<table class="sortable datagrid stripe_tb sortable_ed" id="tablesort_sina_serverIp" cellpadding="0" cellspacing="0" width="100%">
								<thead>

									<tr class="alt">
										<th align="center">ID:</th>
										<td align="center">
											<input type='text' disabled name='id' value="<{$arrUser.id}>" size="40px">
											<input type='hidden' name='id' value="<{$arrUser.id}>" >
										</td>
									</tr>
									<tr class="alt">
										<th align="center">用户名:</th>
										<td align="center">
											<input type='text' name='name' size="40px" value="<{$arrUser.name}>">
										</td>
									</tr>

									<tr class="alt">
										<th align="center">密码:</th>
										<td align="center">
											<input type='text' name='password' size="40px">
										</td>
									</tr>
									<tr class="alt">
										<th align="center">级别:</th>
										<td align="center">
												<select name="level">
													<option value=0 <{if $arrUser.level eq 0 }> selected <{/if}> > 管理员 </option>
													<option value=1 <{if $arrUser.level eq 1 }> selected <{/if}> > 超级管理员</option>
												</select>
										</td>
									</tr>

									<tr class="alt">
										<th align="center">状态:</th>
										<td align="center">
												<select name="status">
													<option value=0 <{if $arrUser.status eq 0 }> selected <{/if}> > 可用 </option>
													<option value=1 <{if $arrUser.status eq 1 }> selected <{/if}> > 禁用 </option>
												</select>
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

<{ include file="App/Public/footer.tpl" }>
