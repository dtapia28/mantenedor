<?php
echo "
                <label for='idResolutor'>Resolutor:</label>        
                <br>                 
                <select class='form-control col-md-3' name='idResolutor'>
                    @foreach($resolutors as $resolutor)
                        <optgroup>
                            <option value={{$resolutor->id}}>{{$resolutor->nombreResolutor}}</option>
                        </optgroup>
                    @endforeach
                </select>
                <a onclick='window.open(this.href, this.target, 'width=300,height=400'); return false;' href='{{ url('/resolutors/nuevo') }}?volver=1'>Crear Resolutor</a>
";                 
?>           