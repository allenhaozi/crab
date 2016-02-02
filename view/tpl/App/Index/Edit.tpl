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
								<span class="fl">App编辑</span>
								<div class="fr">
								</div>
								<div class="clearit"></div>
							</div>
							<!-- second table begin -->
							<form action="<{$arrLink.api_app_save}>" method="post">
							<table class="sortable datagrid stripe_tb sortable_ed" id="tablesort_sina_serverIp" cellpadding="0" cellspacing="0" width="100%">
								<thead>

									<tr class="alt">
										<th align="center">AppID:</th>
										<td align="center">
											<input type='text' disabled name='id' value="<{$arrAppInfo.id}>" size="40px">
											<input type='hidden' name='app_id' value="<{$arrAppInfo.id}>" size="40px">
										</td>
									</tr>
									<tr class="alt">
										<th align="center">AppName:</th>
										<td align="center">
											<input type='text'   name='app_name' value="<{$arrAppInfo.app_name}>" size="40px">
										</td>
									</tr>
									<tr class="alt">
										<th align="center">ServiceType:</th>
										<td align="center">
                                            <!--判断id是否为null，null则请求为新增App，否则请求为编辑App-->
                                            <{if $intEditServiceType neq 1 }>
											    <!--禁止编辑servicetype-->
                                                <input type='text' disabled value="<{if $arrAppInfo.id neq null}><{$arrAppInfo.servicetype}><{/if}>" size="40px" >
											    <input type='hidden' name='servicetype' value="<{$arrAppInfo.servicetype}>" size="40px">
                                            <{else}>
											    <input type='text' name='servicetype' value="<{if $arrAppInfo.id neq null}><{$arrAppInfo.servicetype}><{/if}>" size="40px" onkeyup="suggestServiceType(this.value)">
                                            <{/if}>

										</br><span id='MsgSuggest'></span></td>
									</tr>
									<tr class="alt">
										<th align="center">密钥:</th>
										<td align="center">
											<input type='text' disabled name='app_secret' value="<{$arrAppInfo.app_secret}>" size="40px">
										</td>
									</tr>
									<tr class="alt">
										<th align="center">描述:</th>
										<td align="center">
											<input type='text' name='desc' value="<{$arrAppInfo.desc}>" size="40px" >
										</td>
									</tr>
									<tr class="alt">
										<th align="center">接口人(baidu邮箱):</th>
										<td align="center">
											<input type='text' name='contact' value="<{$arrAppInfo.contact}>" size="40px">
										</td>
									</tr>
									<!--
									<tr class="alt">
										<th align="center">IP白名单(多个IP使用 英文下分号 ; 分割 ):</th>
										<td align="center">
											<textarea type='text' name='ip_list' cols=50 rows=7 ><{$arrAppInfo.iplist}></textarea>
										</td>
									</tr>
									-->
								    <!-- servicetype为非默认值的时候，才可以更新Bridge.js，否则得先生成servicetype -->
                                    <{if $arrAppInfo.servicetype eq '默认值' }>	
									<tr class="alt">
										<th align="center">生成ServiceType:</th>
										<td align="center">
												<select name="new_servicetype">
													<option value=0 selected> 否 </option>
													<option value=1 > 是 </option>
												</select>
										</td>
									</tr>
                                    <{else}>
                                    <tr class="alt">
										<th align="center">更新Bridge.js<font color="red">(影响线上,慎重)</font>:</th>
										<td align="center">
												<select name="is_up_bridge">
													<option value=0 <{if $arrAppInfo.is_up_bridge eq 0 }> selected <{/if}> > 否 </option>
													<option value=1 <{if $arrAppInfo.is_up_bridge eq 1 }> selected <{/if}> > 是 </option>
												</select>
										</td>
									</tr>
								    <{/if}>	
									<tr class="alt">
										<th align="center">状态:</th>
										<td align="center">
												<select name="status">
													<option value=0 <{if $arrAppInfo.status eq 0 }> selected <{/if}> > 可用 </option>
													<option value=1 <{if $arrAppInfo.status eq 1 }> selected <{/if}> > 禁用 </option>
												</select>
										</td>
									</tr>
								</thead>
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
