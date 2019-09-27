						@forelse ($users as $us)
						<tr>
							<th scope="row">
								<a href="../resolutors/{{ $us->id }}">					    
									{{ $us->id }}
								</a>
							</th>
							<td>
								{{ $us->name }}
							</td>
							<td>	
								<form method="POST" action="{{ url('users/modificar') }}">
									{{ csrf_field() }}								
									<select id="role.{$us->id}" class="form-control col-md-5" name="idRole">
										@foreach($relaciones as $relacion)
										@if($us->id == $relacion->user_id)
										@foreach($roles as $role)
										<optgroup>
											<option value="{{ $role->id }}" @if($role->id == $relacion->role_id){{ 'selected' }}@endif>
												{{ $role->nombre }}
											</option>
										</optgroup>
										@endforeach    
										@endif
										@endforeach
									</select>
									<select style="display: none" class='form-control col-md-8' id="team" name='idTeam'></select>
								</td>
								<td>
									<input type="hidden" value="{{ $us->id }}" name="idUsers">	
									<button type="submit" class="btn btn-success">Modificar</button>
								</form>
							</td>								
							@empty	
							@endforelse
						</tr>