function menu_activo(activo) {
    switch (activo) {
        case 'mTablero':
            $('#mTablero').addClass('active');
            $('#mIndicadores').removeClass('active');
            $('#mRequerimientos').removeClass('active');
            $('#mPrioridades').removeClass('active');
            $('#mResolutores').removeClass('active');
            $('#mSolicitantes').removeClass('active');
            $('#mEquipos').removeClass('active');
            $('#mUsuarios').removeClass('active');
            $('#mExportar').removeClass('active');
            break;
        case 'mIndicadores':
            $('#mTablero').removeClass('active');
            $('#mIndicadores').addClass('active');
            $('#mRequerimientos').removeClass('active');
            $('#mPrioridades').removeClass('active');
            $('#mResolutores').removeClass('active');
            $('#mSolicitantes').removeClass('active');
            $('#mEquipos').removeClass('active');
            $('#mUsuarios').removeClass('active');
            $('#mExportar').removeClass('active');
            break;
        case 'mRequerimientos':
            $('#mTablero').removeClass('active');
            $('#mIndicadores').removeClass('active');
            $('#mRequerimientos').addClass('active');
            $('#mPrioridades').removeClass('active');
            $('#mResolutores').removeClass('active');
            $('#mSolicitantes').removeClass('active');
            $('#mEquipos').removeClass('active');
            $('#mUsuarios').removeClass('active');
            $('#mExportar').removeClass('active');
            break;
        case 'mPrioridades':
            $('#mTablero').removeClass('active');
            $('#mIndicadores').removeClass('active');
            $('#mRequerimientos').removeClass('active');
            $('#mPrioridades').addClass('active');
            $('#mResolutores').removeClass('active');
            $('#mSolicitantes').removeClass('active');
            $('#mEquipos').removeClass('active');
            $('#mUsuarios').removeClass('active');
            $('#mExportar').removeClass('active');
            break;
        case 'mResolutores':
            $('#mTablero').removeClass('active');
            $('#mIndicadores').removeClass('active');
            $('#mRequerimientos').removeClass('active');
            $('#mPrioridades').removeClass('active');
            $('#mResolutores').addClass('active');
            $('#mSolicitantes').removeClass('active');
            $('#mEquipos').removeClass('active');
            $('#mUsuarios').removeClass('active');
            $('#mExportar').removeClass('active');
            break;
        case 'mSolicitantes':
            $('#mTablero').removeClass('active');
            $('#mIndicadores').removeClass('active');
            $('#mRequerimientos').removeClass('active');
            $('#mPrioridades').removeClass('active');
            $('#mResolutores').removeClass('active');
            $('#mSolicitantes').addClass('active');
            $('#mEquipos').removeClass('active');
            $('#mUsuarios').removeClass('active');
            $('#mExportar').removeClass('active');
            break;
        case 'mEquipos':
            $('#mTablero').removeClass('active');
            $('#mIndicadores').removeClass('active');
            $('#mRequerimientos').removeClass('active');
            $('#mPrioridades').removeClass('active');
            $('#mResolutores').removeClass('active');
            $('#mSolicitantes').removeClass('active');
            $('#mEquipos').addClass('active');
            $('#mUsuarios').removeClass('active');
            $('#mExportar').removeClass('active');
            break;
        case 'mUsuarios':
            $('#mTablero').removeClass('active');
            $('#mIndicadores').removeClass('active');
            $('#mRequerimientos').removeClass('active');
            $('#mPrioridades').removeClass('active');
            $('#mResolutores').removeClass('active');
            $('#mSolicitantes').removeClass('active');
            $('#mEquipos').removeClass('active');
            $('#mUsuarios').addClass('active');
            $('#mExportar').removeClass('active');
            break;
        case 'mExportar':
            $('#mTablero').removeClass('active');
            $('#mIndicadores').removeClass('active');
            $('#mRequerimientos').removeClass('active');
            $('#mPrioridades').removeClass('active');
            $('#mResolutores').removeClass('active');
            $('#mSolicitantes').removeClass('active');
            $('#mEquipos').removeClass('active');
            $('#mUsuarios').removeClass('active');
            $('#mExportar').addClass('active');
            break;
        default:
            $('#mTablero').removeClass('active');
            $('#mIndicadores').removeClass('active');
            $('#mRequerimientos').removeClass('active');
            $('#mPrioridades').removeClass('active');
            $('#mResolutores').removeClass('active');
            $('#mSolicitantes').removeClass('active');
            $('#mEquipos').removeClass('active');
            $('#mUsuarios').removeClass('active');
            $('#mExportar').removeClass('active');
            break;
    }
}
