% ===============================
% Datos de ejemplo
% ===============================
X = [1 1;
     1 2;
     1 3;
     1 4;
     1 5];   % Matriz de diseño (1 = intercepto)

y = [2; 3; 5; 7; 11];  % Vector de respuestas

% ===============================
% Llamada a la función
% ===============================
beta = regression_householder(X, y);

disp('Coeficientes estimados:');
disp(beta);

% ===============================
% Función de regresión
% ===============================
function beta = regression_householder(X, y)

    [m, n] = size(X);

    % Inicialización
    R = X;
    Q = eye(m);

    % ===============================
    % Descomposición QR por Householder
    % ===============================
    for k = 1:n
        x = R(k:m, k);
        e = zeros(length(x), 1);
        e(1) = norm(x);

        u = x - e;
        u = u / norm(u);

        % Aplicar reflexión de Householder
        R(k:m, k:n) = R(k:m, k:n) - 2 * u * (u' * R(k:m, k:n));
        Q(k:m, :)   = Q(k:m, :)   - 2 * u * (u' * Q(k:m, :));
    end

    % ===============================
    % Resolver el sistema
    % ===============================
    y_hat = Q * y;
    beta = R(1:n, 1:n) \ y_hat(1:n);

end